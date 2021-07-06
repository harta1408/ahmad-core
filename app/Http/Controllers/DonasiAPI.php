<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Models\DonasiTemp;
use App\Models\DonasiCicilan;
use App\Models\Produk;
use App\Models\Bayar;
use App\Models\Donatur;
use Validator;
use GeniusTS\HijriDate\Date;
use GeniusTS\HijriDate\Hijri;
use GeniusTS\HijriDate\Translations\Indonesian;
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
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        $donasino=$this->donasino();
        $jumlah=$request->get('donasi_jumlah_santri');
        $totalharga=$request->get('donasi_total_harga');
        $carabayar=$request->get('donasi_cara_bayar'); 
        $nominal=$request->get('donasi_nominal');

        $donasi=new Donasi();
        $donasi->donasi_no=$donasino;
        $donasi->donatur_id=$request->get('donatur_id');
        $donasi->rekening_id=$request->get('rekening_id');
        $donasi->donasi_tanggal=$request->get('donasi_tanggal');  
        $donasi->donasi_nominal=$nominal;
        $donasi->donasi_jumlah_santri=$jumlah;
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

        //simpan pembayaran dengan status belum dibayar, pembayaran akan berubah status menjadi 
        //sudah di bayar ketika melakukan pengecekan ke rekening bank
        $kodeunik=rand(0,200);
        $bayar=new Bayar;
        $bayar->donasi_id=$donasiid;
        $bayar->bayar_total=$donasi->donasi_total_harga+$kodeunik;
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

        $donasi=Donasi::with('produk','bayar','cicilan')->where('donasi_no',$donasino)->first();
        return response()->json($donasi,200);
    }
 
    #mengambil data donasi berdasarkan id donasi
    public function donasiById($id){
        $donasi=Donasi::with('donatur','produk','bayar')->where('id',$id)->first();
        return response()->json($donasi,200);
    }
    #mengambil data donasi berdasarkan id donasi dan id donatur
    public function donasiDonaturById($donasiid,$donaturid){
        $donasi=Donasi::with('donatur','produk','bayar')
            ->where([['id',$donasiid],['donatur_id',$donaturid]])->first();
        return response()->json($donasi,200);
    }
    #untuk melakukan update rekening ketika proses donasi
    public function donasiUpdateRekening(Request $request,$id){
        $rekeningid=$request->get('rekening_id');
        Donasi::where('id',$id)->update(['rekening_id'=>$rekeningid]);
        $donasi=Donasi::with('donatur','produk','bayar')->where('id',$id)->first();
        return response()->json($donasi,200);
    }
    public function donasiCicilanByDonaturId($id){
        $donatur=function ($query) use ($id){
            $query->where('id',$id);
        };
        $donasi=Donasi::with(['donatur'=>$donatur, 'cicilan'])->whereHas('donatur',$donatur)->get();
        return response()->json($donasi,200);
    }
    public function donasiSantriById($id){
        $donasi=Donasi::with('santri')->where('id',$id)->get();
        return response()->json($donasi,200);
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
    private function prosesCicilan(){

    }
}
