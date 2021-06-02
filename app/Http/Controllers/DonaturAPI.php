<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Donatur;
use App\Models\User;
use App\Models\Donasi;
use App\Models\DonasiTemp;
use App\Models\Produk;
use App\Models\Bayar;
use App\Http\Controllers\DonasiAPI;
use App\Http\Controllers\ReferralAPI;
use Validator;


class DonaturAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
	}
    #register donatur dengan email pribadi hanya menanyakan alamat email
    #sistem mengirimkan email verifikasi untuk penggantian password
    public function donaturRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'user_email' => 'required|email|unique:users|max:100',
            'user_name' => ['required','string','max:30'],
            'url'=>'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        #buat hash code acak untuk default yang harus langsung diganti
        #ketika email terverifikasi
        #link verifikasi di panggil berdasarkan user, nama dan hash code
        $useremail=$request->get('user_email'); 
        $username=$request->get('user_name');
        $url=$request->get('url');
        $temp_donasi_no=$request->get('nomor_donasi');
        $referralid=$request->get('referral_id'); 


        $usertipe="1"; //tipe user donatur
        $hashcode=md5(rand(100000,999999)); 
        $sudahorder=true; //defaulnya donatur sudah order
        $darireferral=true; //defaultnya berasal dari referral


        // periksa apakah pendaftaran ini berdasarkan id referral
        if(!$referralid){
            $darireferral=false;
        }
        
        // periksa apakah sebelum pendaftaran, calon donatur sempat melakukan donasi
        if(!$temp_donasi_no){
            $sudahorder=false;
        }

        #buat user baru dengan alamat email yang dimasukan
        $user=new User;
        $user->user_email=$useremail;
        $user->user_name=$username;
        $user->user_hash_code=$hashcode; 
        $user->user_tipe=$usertipe;
        $exec=$user->save();

        if(!$exec){
            return response()->json(['status' => 'error', 'message' => "Data Cannot be Save", 'code' => 404]);
        }

        #simpan data registrasi donatur
        $donaturkode=$this->donaturKode();
        $donatur=new Donatur;
        $donatur->donatur_kode=$donaturkode;
        $donatur->donatur_email=$useremail; 
        $donatur->donatur_nama=$username;
        $donatur->donatur_status='1'; //aktif belum melengkapi data
        $donatur->save();


        // kirim email registrasi
        // $url=$url.'register?'."idreg=".$hashcode;
        // $data = array('name'=>$username,'url'=>$url);
        // Mail::send('emailregister', $data, function($message) use($useremail, $username) {
        //    $message->to($useremail, $username)->subject
        //       ('no-reply : Pendaftaran AHMaD Project');
        //    $message->from('ahmad@gmail.com','AHMaD Project');
        // });
        // echo "HTML Email Sent. Check your inbox.";

        //jika sudah ada pemesanan produk
        $donaturid=Donatur::where('donatur_email',$useremail)->first()->id;

        if($sudahorder){
            $this->pindahkanDonasi($temp_donasi_no,$donaturid);
            $user=User::with('donatur.donasi')->where('user_email',$useremail)->first();
        }else{
            $user=User::with('donatur')->where('user_email',$useremail)->first();
        }

        //jika berasal dari referral maka cari id pemberi referral kemudian tambahkan
        //penghitung pada minimal, karena yang diberi referral telah mendaftarkan diri
        $refAPI=new ReferralAPI;
        $refAPI->referralUpdateMinimal($referralid,$donaturkode);
        return response()->json($user,200);        
    }
    #register donatur melalui sosial media seperti gmail dan sejenisnya
    #menyimpan email, username, dan password 
    public function donaturRegisterSosmed(Request $request){
        $validator = Validator::make($request->all(), [
            'user_email' => 'required|email|unique:users|max:100',
            'user_name' => 'required|string',
            'user_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        #buat password acak untuk default yang harus langsung diganti
        #ketika email tervirifikasi
        #link verifikasi di panggil berdasarkan user dan password
        $useremail=$request->get('user_email'); 
        $username=$request->get('user_name');
        $password=Hash::make($request->get('user_password')); 
 
        $usertipe="1"; //tipe user donatur

         #buat user baru dengan alamat email yang dimasukan
         $user=new User;
         $user->user_email=$useremail;
         $user->user_password=$password;
         $user->user_name=$username;
         $user->user_tipe=$usertipe;
         $exec=$user->save();

        if(!$exec){
            return response()->json(['status' => 'error', 'message' => "Data Cannot be Save", 'code' => 404]);
        }

        #simpan data registrasi donatur
        $donatur=new Donatur;
        $donatur->donatur_kode=$this->donaturKode();
        $donatur->donatur_nama=$username;
        $donatur->donatur_email=$useremail; 
        $donatur->donatur_status='1'; //aktif belum melengkapi data
        $donatur->save();

        $user=User::with('donatur')->where('user_email',$useremail)->first();
        return response()->json($user,200);    
    }

    private function pindahkanDonasi($temp_donasi_no,$donaturid){
        $donasiTemp=DonasiTemp::with('produk')->where('temp_donasi_no',$temp_donasi_no)->first();
        $donasiapi=new DonasiAPI;

        $donasi=new Donasi;
        $donasino=$donasiapi->donasino();
        $donasi->donasi_no=$donasino;
        $donasi->donatur_id=$donaturid;
        $donasi->donasi_tanggal=$donasiTemp->temp_donasi_tanggal;  
        $donasi->rekening_id=$donasiTemp->rekening_id;
        $donasi->donasi_tagih=$donasiTemp->temp_donasi_tagih;
        $donasi->donasi_jumlah_santri=$donasiTemp->temp_donasi_jumlah_santri;
        $donasi->donasi_total_harga=$donasiTemp->temp_donasi_total_harga;
        $donasi->donasi_cara_bayar=$donasiTemp->temp_donasi_cara_bayar; //cara pembayaran 1=harian, 2=mingguan, 3=bulanan 4=tunai
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
        //simpan pembayaran dengan status belum dibayar, pembayaran akan berubah status menjadi 
        //sudah di bayar ketika melakukan pengecekan ke rekening bank
        $kodeunik=rand(0,999);
        $bayar=new Bayar;
        $bayar->donasi_id=$id;
        $bayar->bayar_total=$donasi->donasi_total_harga;
        $bayar->bayar_kode_unik=$kodeunik;
        $bayar->bayar_disc=0;
        $bayar->bayar_onkir=0;
        $bayar->bayar_status=1;
        //hapus pemesanan sementara sebelum register
        $donasitemp=DonasiTemp::where('temp_donasi_no',$temp_donasi_no)->first()->produk()->detach();
        $donasitemp=DonasiTemp::where('temp_donasi_no',$temp_donasi_no)->delete();
        return $donasi;
    }

    #memperbaharui profile donatur
    public function donaturUpdateProfile($id,Request $request){
        $validator = Validator::make($request->all(), [
            'donatur_nama' => 'required|string',
            'donatur_telepon' => 'required|string',
            'donatur_alamat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $exec=Donatur::where('id','=' ,$id)
        ->update(['donatur_id'=>$request->get('donatur_id'),
                  'donatur_nama'=>$request->get('donatur_nama'),
                  'donatur_tmp_lahir'=>$request->get('donatur_tmp_lahir'), 
                  'donatur_tgl_lahir'=>$request->get('donatur_tgl_lahir'), 
                  'donatur_gender'=>$request->get('donatur_gender'), 
                  'donatur_agama'=>$request->get('donatur_agama'), 
                  'donatur_telepon'=>$request->get('donatur_telepon'), 
                  'donatur_lokasi_photo'=>$request->get('donatur_lokasi_photo'), 
                  'donatur_kerja'=>$request->get('donatur_kerja'),
                  'donatur_alamat'=>$request->get('donatur_alamat'), 
                  'donatur_kode_pos'=>$request->get('donatur_kode_pos'),
                  'donatur_kelurahan'=>$request->get('donatur_kelurahan'),
                  'donatur_kecamatan'=>$request->get('donatur_kecamatan'),
                  'donatur_kota'=>$request->get('donatur_kota'),
                  'donatur_provinsi'=>$request->get('donatur_provinsi'),
                  'donatur_status' => '2', //data sudah lengkap
                  ]);
        $donatur=Donatur::where('id',$id)->first();
        return response()->json($donatur,200);
    }
    #mengambil data donatur berdasarkan emai
    public function donaturByEmail($email){
        $donatur=Donatur::where('donatur_email',$email)->first();
        return response()->json($donatur,200);
    }
    public function donaturList(){
        $donatur=Donatur::where('donatur_status','1')->get();
        return response()->json($donatur,200);
    }
    public function donaturById($id){
        $donatur=Donatur::where('id',$id)->first();
        return response()->json($donatur,200);
    }
   
    public function donaturKode()
    {
      //otomatis pengaturan kode santri dengan format 
      //tahun[2]+bulan[2]+nomor urut[4]
      $bulan=date("m");
      $tahun=date("y");
      $usertipe="1"; //tipe user donatur
      $strNewId = $usertipe.$tahun.$bulan."0001";

      while ($this->findDonaturKode($strNewId)) { 
        $intNewId=substr($strNewId,-4)+1; 
        switch (strlen($intNewId)) {
            case 1:
                $strNewId=$usertipe.$tahun.$bulan.'000'.$intNewId;
                break;
            case 2:
                $strNewId=$usertipe.$tahun.$bulan.'00'.$intNewId;
                break;
            case 3:
                $strNewId=$usertipe.$tahun.$bulan.'0'.$intNewId;
                break;
            case 4:
                $strNewId=$usertipe.$tahun.$bulan.$intNewId;
                break;  
        }

      }
      return $strNewId;
    }
    private function findDonaturKode($donaturKode){
        $donatur=Donatur::where('donatur_kode',$donaturKode)->first();
        if($donatur){
          return true;
        }
        return false;
    }
}
