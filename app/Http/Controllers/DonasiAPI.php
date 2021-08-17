<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Models\DonasiTemp;
use App\Models\DonasiCicilan;
use App\Models\Produk;
use App\Models\Bayar;
use App\Models\Donatur;
use App\Models\Lembaga;
use Validator;
use GeniusTS\HijriDate\Date;
use GeniusTS\HijriDate\Hijri;
use GeniusTS\HijriDate\Translations\Indonesian;
use App\Http\Controllers\Service\MessageService;
use App\Http\Controllers\Service\AccountService;
class DonasiAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
	}

    #untuk eksekusi penambahan donatur dengan donasi, maka harus eksekusi 
    #donasitempsimpan, agar bisa mendapatkan nomor donasinya
    public function donasiTempSimpan(Request $request){
        $tempdonasino=rand(100000,999999);
        $donasitemp=new DonasiTemp;
        $donasitemp->temp_donasi_no=$tempdonasino;
        $donasitemp->rekening_id=$request->get('rekening_id');
        $donasitemp->temp_donasi_tanggal=$request->get('donasi_tanggal');  
        $donasitemp->temp_donasi_jumlah_santri=$request->get('donasi_jumlah_santri');
        $donasitemp->temp_donasi_nominal=$request->get('temp_donasi_nominal'); 
        $donasitemp->temp_donasi_total_harga=$request->get('donasi_total_harga');
        $donasitemp->temp_donasi_cara_bayar=$request->get('donasi_cara_bayar'); 
        $donasitemp->temp_donasi_random_santri=$request->get('donasi_random_santri'); 
        $donasitemp->temp_donasi_kode_unik=$request->get("temp_donasi_kode_unik");
        $donasitemp->save();

        // $donasino=DonasiTemp::where('temp_donasi_no',$tempdonasino)->first()->id;
        for ($i=0; $i < count($request->input('donasiproduk')) ; $i++) {
            $produkid=$request->input('donasiproduk')[$i]['produk_id'];
            $produk=Produk::where('id',$produkid)->first(); 
            $donasitemp->produk()->attach(['produk_id'=>$produkid],
                [
                    'temp_donasi_no'=>$tempdonasino,
                    'temp_donasi_produk_jml' => $request->input('donasiproduk')[$i]['temp_donasi_produk_jml'] ,
                    'temp_donasi_produk_harga' =>$request->input('donasiproduk')[$i]['temp_donasi_produk_harga'],
                    'temp_donasi_produk_total' => $request->input('donasiproduk')[$i]['temp_donasi_produk_total'],
                ]);
        }
        $donasi=DonasiTemp::with('produk')->where('temp_donasi_no',$tempdonasino)->first();
        return response()->json($donasi,200);
    }
    #simpan donasi
    public function donasiSimpan(Request $request){
        $validator = Validator::make($request->all(), [
            'donasi_jumlah_santri' => 'required|integer',
            'donasi_total_harga' => 'required|integer',
            'donasi_cara_bayar' => 'required|integer',
            'donasi_nominal' => 'required|integer',
            'donasi_random_santri' =>'required|string',
            'donasi_kode_unik' =>'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        #penyesuaian tanggal hijriah
        $adjhijr=Lembaga::first()->lembaga_adjust_hijr;
        Hijri::setDefaultAdjustment($adjhijr);

        $donasino=$this->donasino();
        $jumlah=$request->get('donasi_jumlah_santri');
        $totalharga=$request->get('donasi_total_harga');
        $carabayar=$request->get('donasi_cara_bayar'); 
        $nominal=$request->get('donasi_nominal');
        $kodeunik=$request->get('donasi_kode_unik');

        $donasi=new Donasi();
        $donasi->donasi_no=$donasino;
        $donasi->donatur_id=$request->get('donatur_id');
        $donasi->rekening_id=$request->get('rekening_id');
        $donasi->donasi_tanggal=$request->get('donasi_tanggal');  
        $donasi->donasi_random_santri=$request->get('donasi_random_santri');
        $donasi->donasi_nominal=$nominal;
        $donasi->donasi_jumlah_santri=$jumlah;
        $donasi->donasi_sisa_santri=$jumlah;
        $donasi->donasi_total_harga=$totalharga;
        $donasi->donasi_cara_bayar=$carabayar;//cara pembayaran 1=harian, 2=mingguan, 3=bulanan 4=tunai
        $donasi->donasi_status='1'; //donasi disimpan, belum di bayar
        $donasi->save();

        $donasiid=Donasi::where('donasi_no',$donasino)->first()->id;
        
        for ($i=0; $i < count($request->input('donasiproduk')) ; $i++) {
            $produkid=$request->input('donasiproduk')[$i]['produk_id'];
            $produk=Produk::where('id',$produkid)->first(); 
            $donasi->produk()->attach(['produk_id'=>$produkid],
                [
                    'donasi_id'=>$donasiid,
                    'donasi_produk_jml' => $request->input('donasiproduk')[$i]['donasi_produk_jml'] ,
                    'donasi_produk_harga' =>$request->input('donasiproduk')[$i]['donasi_produk_harga'],
                    'donasi_produk_total' => $request->input('donasiproduk')[$i]['donasi_produk_total'],
                ]);
        }

        #proses jadwal cicilan
        $jumlahcicilan=$totalharga/$nominal;
        $datehijr = 0;
        $blnhijr=0;
        $thnhijr=1;
        $todaydate=date("Y-m-d");
        $datehijr = Hijri::convertToHijri($todaydate);
        $blnhijr=$datehijr->format('m');
        $thnhijr=$datehijr->format('Y');
        for ($i=0; $i < $jumlahcicilan; $i++) { 
            if($i=='0'){ //khusus hari pertama atau cara bayar tunai, jatuh tempo pada hari yang sama
                $dayno=$i." days";
                $date=date("Y-m-d",strtotime($dayno));
                $yaumilbidh=Hijri::convertToHijri($date)->format('d-m-Y');
            }else{
                if($carabayar=='1' ){
                    $dayno=$i." days";
                    $date=date("Y-m-d",strtotime($dayno));
                    $yaumilbidh=Hijri::convertToHijri($date)->format('d-m-Y');
                }
                if($carabayar=='2'){
                    #jika tanggal bukan jumat, maka geser dulu ke hari jumat
                    if(date('w', strtotime($todaydate))!=5){ //5:friday
                        $interval=5-date('w', strtotime($todaydate)); 
                        $weekno=$i." weeks ".$interval." days";
                    }else{
                        $weekno=$i." weeks";
                    }
                    $date=date("Y-m-d",strtotime($weekno));
                    $yaumilbidh=Hijri::convertToHijri($date)->format('d-m-Y');
                }
                if($carabayar=='3'){
                    #generate tanggal yaumil bidh (hijriah) secara acak
                    $blnhijr=$blnhijr+1;
                    if($blnhijr>=12){
                        $thnhijr=$thnhijr+1;
                        $blnhijr=1;
                    }
    
                    if(strlen($blnhijr)==1){
                        $blnhijr='0'.$blnhijr;
                    }
                    //karena ada plus minus tanggal hijriah lebih aman pilih yang tengah
                    $randhijrdate='14'; //rand(13,15); //pilih tanggal yaumil bidh secara acak 
            
                    $yaumilbidh=$randhijrdate.'-'.$blnhijr.'-'.$thnhijr;
                    $date=Hijri::convertToGregorian(13,$blnhijr,$thnhijr);
                }
            }
            $cicilan=new DonasiCicilan;
            $cicilan->donasi_id=$donasiid;
            $cicilan->cicilan_ke=$i+1;
            $cicilan->cicilan_jatuh_tempo=$date;
            $cicilan->cicilan_hijr=$yaumilbidh;
            $cicilan->cicilan_nominal=$nominal;
            $cicilan->cicilan_status='1';
            $cicilan->save();
        }
        //simpan pembayaran dengan status belum dibayar, pembayaran akan berubah status menjadi 
        //sudah di bayar ketika melakukan pengecekan ke rekening bank
        //pembayaran mengacu kepada cicilan
        //ambil cicilan pertama
        $cicilan=DonasiCicilan::where([['donasi_id',$donasiid],['cicilan_ke','1']])->first();

        // $kodeunik=rand(0,100); di handle UI
        $bayar=new Bayar;
        $bayar->cicilan_id=$cicilan->id;
        // $bayar->bayar_tanggal=$todaydate; //di isi pada saat status bayar 2
        $bayar->bayar_total=$cicilan->cicilan_nominal+$kodeunik;
        $bayar->bayar_kode_unik=$kodeunik;
        $bayar->bayar_disc=0;
        $bayar->bayar_onkir=0;
        $bayar->bayar_status=1;
        $bayar->save();

        #kirimkan pesan via email untuk donasi yang di maksud
        $msg=new MessageService;
        $donasi=Donasi::where('id',$donasiid)->first();
        $donatur=Donatur::where('id',$donasi->donatur_id)->first();
        $donaturnama=$donatur->donatur_nama;
        $donaturemail=$donatur->donatur_email;
        $msg->kirimEmailDonasiCicilan($donaturemail,$donaturnama,$donasiid);

        $donasi=Donasi::with('produk','cicilan.bayar')->where('donasi_no',$donasino)->first();
        return response()->json($donasi,200);
    }
 
    #mengambil data donasi berdasarkan id donasi
    public function donasiById($id){
        $donasi=Donasi::with('donatur','produk')->where('id',$id)->first();
        return response()->json($donasi,200);
    }
    #mengambil data donasi berdasarkan id donasi dan id donatur
    public function donasiDonaturById($donasiid,$donaturid){
        $donasi=Donasi::with('donatur','produk')
            ->where([['id',$donasiid],['donatur_id',$donaturid]])->first();
        return response()->json($donasi,200);
    }
    #untuk melakukan update rekening ketika proses donasi
    public function donasiUpdateRekening(Request $request,$id){
        $rekeningid=$request->get('rekening_id');
        Donasi::where('id',$id)->update(['rekening_id'=>$rekeningid]);
        $donasi=Donasi::with('donatur','produk')->where('id',$id)->first();
        return response()->json($donasi,200);
    }
    public function donasiByDonaturId($donaturid){
        $donasi=Donasi::where('donatur_id',$donaturid)->get();
        return response()->json($donasi,200);
    }
    public function donasiCicilanByDonaturId($donaturid){
        $donatur=function ($query) use ($donaturid){
            $query->where('id',$donaturid);
        };
        $donasi=Donasi::with(['donatur'=>$donatur, 'cicilan.bayar'])->whereHas('donatur',$donatur)->get();
        return response()->json($donasi,200);
    }
    public function donasiOutstandingByDonaturId($donaturid){
        $cicilan=function ($query) {
            $query->with('bayar')->where('cicilan_status','1');
        };
        $donasi=Donasi::with(['cicilan'=>$cicilan])->whereHas('cicilan',$cicilan)->get();
        return response()->json($donasi,200);
    }
    public function donasiPaidByDonaturId($donaturid){
        $cicilan=function ($query) {
            $query->with('bayar')->where('cicilan_status','2');
        };
        $donasi=Donasi::with(['cicilan'=>$cicilan])->whereHas('cicilan',$cicilan)->get();
        return response()->json($donasi,200);
    }
    public function donasiSantriById($id){
        $donasi=Donasi::with('santri')->where('id',$id)->get();
        return response()->json($donasi,200);
    }

    public function pindahkanDonasi($temp_donasi_no,$donaturid){
        #penyesuaian tanggal hijriah
        $adjhijr=Lembaga::first()->lembaga_adjust_hijr;
        Hijri::setDefaultAdjustment($adjhijr);

        $donasiTemp=DonasiTemp::with('produk')->where('temp_donasi_no',$temp_donasi_no)->first();
        $donasiapi=new DonasiAPI;

        $donasino=$donasiapi->donasino();
        $jumlah=$donasiTemp->temp_donasi_jumlah_santri;
        $totalharga=$donasiTemp->temp_donasi_total_harga;
        $carabayar=$donasiTemp->temp_donasi_cara_bayar; 
        $nominal=$donasiTemp->temp_donasi_nominal;
        $kodeunik=$donasiTemp->temp_donasi_kode_unik;

        $donasi=new Donasi;
        $donasi->donasi_no=$donasino;
        $donasi->donatur_id=$donaturid;
        $donasi->donasi_tanggal=$donasiTemp->temp_donasi_tanggal;  
        $donasi->rekening_id=$donasiTemp->rekening_id;
        $donasi->donasi_nominal=$nominal;
        $donasi->donasi_jumlah_santri=$jumlah;
        $donasi->donasi_sisa_santri=$jumlah;
        $donasi->donasi_total_harga=$totalharga;
        $donasi->donasi_cara_bayar=$carabayar; //cara pembayaran 1=harian, 2=mingguan, 3=bulanan 4=tunai
        $donasi->donasi_status='1'; //donasi disimpan, belum di bayar
        $donasi->save();

        $donasiid=Donasi::where('donasi_no',$donasino)->first()->id;
        foreach ($donasiTemp->produk as $key => $produk) {
            $produkid=$produk->id;
            // var_dump($produk);
            // dd($produk->donasiproduktemp['temp_donasi_produk_harga']);
            $donasiprodukjml=$produk->donasiproduktemp['temp_donasi_produk_jml'];
            $donasiprodukharga=$produk->donasiproduktemp['temp_donasi_produk_harga'];
            $donasiproduktotal=$produk->donasiproduktemp['temp_donasi_produk_total'];
            $produk=Produk::where('id',$produkid)->first(); 
            $donasi->produk()->attach(['produk_id'=>$produkid],
                [
                    'donasi_id'=>$donasiid,
                    'donasi_produk_jml' => $donasiprodukjml,
                    'donasi_produk_harga' =>$donasiprodukharga,
                    'donasi_produk_total' =>$donasiproduktotal,
                ]);
        }

        #proses jadwal cicilan
        $jumlahcicilan=$totalharga/$nominal;
        $datehijr = 0;
        $blnhijr=0;
        $thnhijr=1;
        $todaydate=date("Y-m-d");
        $datehijr = Hijri::convertToHijri($todaydate);
        $blnhijr=$datehijr->format('m');
        $thnhijr=$datehijr->format('Y');
        for ($i=0; $i < $jumlahcicilan; $i++) { 
            if($i=='0'){ //khusus hari pertama atau cara bayar tunai, jatuh tempo pada hari yang sama
                $dayno=$i." days";
                $date=date("Y-m-d",strtotime($dayno));
                $yaumilbidh=Hijri::convertToHijri($date)->format('d-m-Y');
            }else{
                if($carabayar=='1'){
                    $dayno=$i." days";
                    $date=date("Y-m-d",strtotime($dayno));
                    $yaumilbidh=Hijri::convertToHijri($date)->format('d-m-Y');
                }
                if($carabayar=='2'){
                    #jika tanggal bukan jumat, maka geser dulu ke hari jumat
                    $todaydate=date("Y-m-d");
                    if(date('w', strtotime($todaydate))!=5){ //5:friday
                        $interval=5-date('w', strtotime($todaydate)); 
                        $weekno=$i." weeks ".$interval." days";
                    }else{
                        $weekno=$i." weeks";
                    }
                    $date=date("Y-m-d",strtotime($weekno));
                    $yaumilbidh=Hijri::convertToHijri($date)->format('d-m-Y');
                }
                if($carabayar=='3'){
                    #generate tanggal yaumil bidh (hijriah) secara acak
                    $blnhijr=$blnhijr+1;
                    if($blnhijr>=12){
                        $thnhijr=$thnhijr+1;
                        $blnhijr=1;
                    }

                    if(strlen($blnhijr)==1){
                        $blnhijr='0'.$blnhijr;
                    }
                    //karena ada plus minus tanggal hijriah lebih aman pilih yang tengah
                    $randhijrdate='14'; //rand(13,15); //pilih tanggal yaumil bidh secara acak 
            
                    $yaumilbidh=$randhijrdate.'-'.$blnhijr.'-'.$thnhijr;
                    $date=Hijri::convertToGregorian(13,$blnhijr,$thnhijr);
                }
            }
            $cicilan=new DonasiCicilan;
            $cicilan->donasi_id=$donasiid;
            $cicilan->cicilan_ke=$i+1;
            $cicilan->cicilan_jatuh_tempo=$date;
            $cicilan->cicilan_hijr=$yaumilbidh;
            $cicilan->cicilan_nominal=$nominal;
            $cicilan->cicilan_status='1';
            $cicilan->save();
        }
        //simpan pembayaran dengan status belum dibayar, pembayaran akan berubah status menjadi 
        //sudah di bayar ketika melakukan pengecekan ke rekening bank
        //pembayaran mengacu kepada cicilan
        //ambil cicilan pertama
        $cicilan=DonasiCicilan::where([['donasi_id',$donasiid],['cicilan_ke','1']])->first();

        // $kodeunik=rand(0,100); /di handle UI
        $bayar=new Bayar;
        $bayar->cicilan_id=$cicilan->id;

        // $bayar->bayar_tanggal=$todaydate; //di isi pada saat status bayar 2
        $bayar->bayar_total=$cicilan->cicilan_nominal+$kodeunik;
        $bayar->bayar_kode_unik=$kodeunik;
        $bayar->bayar_disc=0;
        $bayar->bayar_onkir=0;
        $bayar->bayar_status=1;
        $bayar->save();

        #proses jadwal cicilan
        $jumlahcicilan=$totalharga/$nominal;
        $datehijr = 0;
        $blnhijr=0;
        $thnhijr=1;
        $todaydate=date("Y-m-d");
        $datehijr = Hijri::convertToHijri($todaydate);
        $blnhijr=$datehijr->format('m');
        $thnhijr=$datehijr->format('Y');
        for ($i=1; $i <= $jumlahcicilan; $i++) { 
             if($carabayar=='1'){
                 $dayno=$i." days";
                 $date=date("Y-m-d",strtotime($dayno));
                 $yaumilbidh=Hijri::convertToHijri($date)->format('d-m-Y');
             }
             if($carabayar=='2'){
                 #jika tanggal bukan jumat, maka geser dulu ke hari jumat
                 $todaydate=date("Y-m-d");
                 if(date('w', strtotime($todaydate))!=5){ //5:friday
                     $interval=5-date('w', strtotime($todaydate)); 
                     $weekno=$i." weeks ".$interval." days";
                 }else{
                     $weekno=$i." weeks";
                 }
                 $date=date("Y-m-d",strtotime($weekno));
                 $yaumilbidh=Hijri::convertToHijri($date)->format('d-m-Y');
             }
             if($carabayar=='3'){
                 #generate tanggal yaumil bidh (hijriah) secara acak
                 $blnhijr=$blnhijr+1;
                 if($blnhijr>=12){
                     $thnhijr=$thnhijr+1;
                     $blnhijr=1;
                 }
 
                 if(strlen($blnhijr)==1){
                     $blnhijr='0'.$blnhijr;
                 }
                 $randhijrdate=rand(13,15); //pilih tanggal yaumil bidh secara acak
         
                 $yaumilbidh=$randhijrdate.'-'.$blnhijr.'-'.$thnhijr;
                 $date=Hijri::convertToGregorian(13,$blnhijr,$thnhijr);
             }
             $cicilan=new DonasiCicilan;
             $cicilan->donasi_id=$donasiid;
             $cicilan->cicilan_ke=$i;
             $cicilan->cicilan_jatuh_tempo=$date;
             $cicilan->cicilan_hijr=$yaumilbidh;
             $cicilan->cicilan_nominal=$nominal;
             $cicilan->cicilan_status='1';
             $cicilan->save();
        }
 
        #kirimkan pesan via email untuk donasi yang di maksud
        $msg=new MessageService;
        $donasi=Donasi::where('id',$donasiid)->first();
        $donatur=Donatur::where('id',$donasi->donatur_id)->first();
        $donaturnama=$donatur->donatur_nama;
        $donaturemail=$donatur->donatur_email;
        $msg->kirimEmailDonasiCicilan($donaturemail,$donaturnama,$donasiid);

        $donasi=Donasi::with('produk','cicilan')->where('donasi_no',$donasino)->first();

        //hapus pemesanan sementara sebelum register
        $donasitemp=DonasiTemp::where('temp_donasi_no',$temp_donasi_no)->first()->produk()->detach();
        $donasitemp=DonasiTemp::where('temp_donasi_no',$temp_donasi_no)->delete();
        return $donasi;
    }

    #modul untuk memeriksa pembayaran donasi berdasarkan id donasi, pengecekan melalui aplikasi
    #dilakukan sesuai dengan waktu yang di minta (timer ada di sisi UI)
    public function donasiPeriksaPembayaran(Request $request){
        $cicilanid=$request->get('cicilan_id');
        $tglbayar=$request->get('tanggal_bayar'); //format YYYY-MM-DD;

        $donasicicilan=DonasiCicilan::where('id',$cicilanid)->first();
        $cicilanstatus=$donasicicilan->cicilan_status;
        if($cicilanstatus=='2'){
            return response()->json(['status' => 'error', 'message' => 'Cicilan Sudah di Bayar', 'code' => 404]);
        }
        $datestart=$tglbayar;
        $dateend=$tglbayar;

        $accservice=new AccountService;
        $result=$accservice->mootaCekBayarDonasiByCicilanId($cicilanid,$datestart,$dateend);
        if($result==false){
            return response()->json(['status' => 'error', 'message' => 'Tidak ada data yang di proses', 'code' => 404]);
        }
        return response()->json(['status' => 'success', 'message' => 'Pembayaran Berhasil di Verifikasi', 'code' => 404]);
    }
    
    public function donasino()
    {
      //otomatis pengaturan nomor donasi dengan format 
      //tahun[2]+bulan[2]+nomor urut[6]
      $bulan=date("m");
      $tahun=date("y");
      $strNewId = $tahun.$bulan."000001";
      while ($this->findDonasiKode($strNewId)) { 
        $intNewId=substr($strNewId,-6)+1; 
        switch (strlen($intNewId)) {
            case 1:
                $strNewId=$tahun.$bulan.'00000'.$intNewId;
                break;
            case 2:
                $strNewId=$tahun.$bulan.'0000'.$intNewId;
                break;
            case 3:
                $strNewId=$tahun.$bulan.'000'.$intNewId;
                break;
            case 4:
                $strNewId=$tahun.$bulan.'00'.$intNewId;
                break;  
            case 5:
                $strNewId=$tahun.$bulan.'0'.$intNewId;
                break;
            case 6:
                $strNewId=$tahun.$bulan.$intNewId;
                break;  
        }
      }
      return $strNewId;
    }
    private function findDonasiKode($donasino){
        $donasi=Donasi::where('donasi_no',$donasino)->first();
        if($donasi){
          return true;
        }
        return false;
    }

}
