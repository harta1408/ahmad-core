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
use App\Models\DonasiCicilan;
use App\Models\Materi;
use App\Models\Bimbingan;
use App\Models\BimbinganMateri;
use App\Models\DonaturSantri;
use App\Models\Santri;
use App\Http\Controllers\DonasiAPI;
use App\Http\Controllers\ReferralAPI;
use App\Http\Controllers\Service\MessageService;
use GeniusTS\HijriDate\Date;
use GeniusTS\HijriDate\Hijri;
use Config;
use Validator;
class DonaturAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
	}
    #register donatur dengan email pribadi hanya menanyakan alamat email
    #sistem mengirimkan email verifikasi untuk penggantian password

    #registrasi donatur normal
    public function donaturRegister(Request $request){
        #validasi
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users|max:100',
            'name' => ['required','string','max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        #buat hash code acak untuk default yang harus langsung diganti
        #ketika email terverifikasi
        #link verifikasi di panggil berdasarkan user, nama dan hash code
        $useremail=$request->get('email'); 
        $username=$request->get('name');
        $usertipe="1"; //tipe user donatur
        $hashcode=md5(rand(100000,999999));  

        #buat user baru dengan alamat email yang dimasukan
        $user=new User;
        $user->email=$useremail;
        $user->name=$username;
        $user->hash_code=$hashcode; 
        $user->password=$hashcode;
        $user->tipe=$usertipe;
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

        #ambil user berdasarkan email
        $user=User::with('donatur')->where('email',$useremail)->first();

        $msg=new MessageService;

        #kirim email verifikasi
        $msg->kirimEmailVerifikasi($useremail,$username,$hashcode);
        #simpan/kirim pesan
        $msg->simpanNotifikasiSelamatBergabung('0',$user->id);
        return response()->json($user,200);        
    }

    #registrasi donatur dengan donasi
    public function donaturRegisterDonasi(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users|max:100',
            'name' => ['required','string','max:100'],
            'nomor_donasi' =>['required','string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        #buat hash code acak untuk default yang harus langsung diganti
        #ketika email terverifikasi
        #link verifikasi di panggil berdasarkan user, nama dan hash code
        $useremail=$request->get('email'); 
        $username=$request->get('name');
        $temp_donasi_no=$request->get('nomor_donasi');

        $usertipe="1"; //tipe user donatur
        $hashcode=md5(rand(100000,999999)); 

        #buat user baru dengan alamat email yang dimasukan
        $user=new User;
        $user->email=$useremail;
        $user->name=$username;
        $user->hash_code=$hashcode; 
        $user->password=$hashcode;
        $user->tipe=$usertipe;
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

        //jika sudah ada pemesanan produk
        $donaturid=Donatur::where('donatur_email',$useremail)->first()->id;
        $donasiAPI=new DonasiAPI;
        $donasiAPI->pindahkanDonasi($temp_donasi_no,$donaturid);
        
        #ambil user berdasarkan email
        $user=User::with('donatur.donasi')->where('email',$useremail)->first();

        $msg=new MessageService;

        #kirim email verifikasi
        $msg->kirimEmailVerifikasi($useremail,$username,$hashcode);
        #simpan/kirim pesan
        $msg->simpanNotifikasiSelamatBergabung('0',$user->id);
        return response()->json($user,200);                
    }


    #registrasi donatur dengan referral
    public function donaturRegisterReferral(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users|max:100',
            'name' => ['required','string','max:100'],
            'referral_id'=> ['required','string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        #buat hash code acak untuk default yang harus langsung diganti
        #ketika email terverifikasi
        #link verifikasi di panggil berdasarkan user, nama dan hash code
        $useremail=$request->get('email'); 
        $username=$request->get('name');
        $referralid=$request->get('referral_id'); 
        $usertipe="1"; //tipe user donatur
        $hashcode=md5(rand(100000,999999)); 

        #buat user baru dengan alamat email yang dimasukan
        $user=new User;
        $user->email=$useremail;
        $user->name=$username;
        $user->hash_code=$hashcode; 
        $user->password=$hashcode;
        $user->tipe=$usertipe;
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

        $refAPI=new ReferralAPI;
        $refAPI->referralUpdateMinimal($referralid,$donaturkode);

        #ambil user berdasarkan email
        $user=User::with('donatur')->where('email',$useremail)->first();
        $msg=new MessageService;

        #kirim email verifikasi
        $msg->kirimEmailVerifikasi($useremail,$username,$hashcode);
        #simpan/kirim pesan
        $msg->simpanNotifikasiSelamatBergabung('0',$user->id);
        return response()->json($user,200);        
    }

    #registrasi donatur dengan donasi dan referral
    public function donaturRegisterDonasiReferral(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users|max:100',
            'name' => ['required','string','max:50'],
            'nomor_donasi' =>['required','string'],
            'referral_id'=> ['required','string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        #buat hash code acak untuk default yang harus langsung diganti
        #ketika email terverifikasi
        #link verifikasi di panggil berdasarkan user, nama dan hash code
        $useremail=$request->get('email'); 
        $username=$request->get('name');
        $temp_donasi_no=$request->get('nomor_donasi');
        $referralid=$request->get('referral_id'); 
        $usertipe="1"; //tipe user donatur
        $hashcode=md5(rand(100000,999999));  
 
        #buat user baru dengan alamat email yang dimasukan
        $user=new User;
        $user->email=$useremail;
        $user->name=$username;
        $user->hash_code=$hashcode; 
        $user->password=$hashcode;
        $user->tipe=$usertipe;
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

        //jika sudah ada pemesanan produk
        $donaturid=Donatur::where('donatur_email',$useremail)->first()->id;
        $donasiAPI=new DonasiAPI;
        $donasiAPI->pindahkanDonasi($temp_donasi_no,$donaturid);
        $user=User::with('donatur.donasi')->where('email',$useremail)->first();

        //jika berasal dari referral maka cari id pemberi referral kemudian tambahkan
        //penghitung pada minimal, karena yang diberi referral telah mendaftarkan diri
        $refAPI=new ReferralAPI;
        $refAPI->referralUpdateMinimal($referralid,$donaturkode);

        #ambil user berdasarkan email
        $user=User::with('donatur')->where('email',$useremail)->first();

        $msg=new MessageService;
        #kirim email verifikasi
        $msg->kirimEmailVerifikasi($useremail,$username,$hashcode);
        #simpan/kirim pesan
        $msg->simpanNotifikasiSelamatBergabung('0',$user->id);
        return response()->json($user,200);         
    }


    #register donatur melalui sosial media seperti gmail dan sejenisnya
    #menyimpan email, username, dan password 
    public function donaturRegisterGMail(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users|max:100',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        #buat password acak untuk default yang harus langsung diganti
        #ketika email tervirifikasi
        #link verifikasi di panggil berdasarkan user dan password
        $useremail=$request->get('email'); 
        $username=$request->get('name'); 
        $usertipe="1"; //tipe user donatur
        $gmailstate='1'; //berasal dari gmail
        $hashcode=md5(rand(100000,999999));  

        #buat user baru dengan alamat email yang dimasukan
        $user=new User;
        $user->email=$useremail;
        $user->hash_code=$hashcode; 
        $user->password=$hashcode;
        $user->name=$username;
        $user->tipe=$usertipe;
        $user->gmail_state=$gmailstate;
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

        #ambil user berdasarkan email
        $user=User::with('donatur')->where('email',$useremail)->first();

        $msg=new MessageService;

        #kirim email verifikasi
        $msg->kirimEmailVerifikasi($useremail,$username,$hashcode);
        #simpan/kirim pesan
        $msg->simpanNotifikasiSelamatBergabung('0',$user->id);
        return response()->json($user,200);    
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
        ->update(['donatur_nid'=>$request->get('donatur_nid'),
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
    public function donasiSantriById($donaturid){
        $santri=function ($query) {
            $query->with('kirimproduk');
        };
        $donatur=Donatur::with(['santri'=>$santri])->whereHas('santri',$santri)->where('id',$donaturid)->get();
        return response()->json($donatur,200);
    }
    public function donaturUploadImage(Request $request){
        //ambil id donatur, kemudian cari di database kodenya
        $id=$request->get('id');  
        $donatur_kode=Donatur::where('id',$id)->first()->donatur_kode;
      
        // menyimpan data file yang diupload ke variabel $file
        $images = $request->file('donatur_photo');
        $new_name=$donatur_kode.'.'.$images->getClientOriginalExtension();

        //tujuan penyimpanan file
        $tujuan_upload = base_path("images");
        $images->move($tujuan_upload,$new_name); 

        $fileloc=substr($request->root(),0,strlen($request->root())-6) ."images/".$new_name; //upload ke server
        // $fileloc=$request->root()."/"."images/".$new_name;
    
        Donatur::where('donatur_kode','=',$donatur_kode)->update(['donatur_lokasi_photo'=>$fileloc]);

        $donatur=Donatur::where('id',$id)->first();
        return response()->json($donatur,200);
    }
    public function donaturDashboard($donaturid){
        #validasi
        $materi=Materi::where('materi_status','1')->get();
        if(!$materi){
            return response()->json(['status' => 'error', 'message' => 'Belum ada Materi', 'code' => 404]);
        }

        $jmldonasi=Donasi::where('donatur_id',$donaturid)->sum('donasi_jumlah_santri');
        $jmltersalurkan=DonaturSantri::where('donatur_id',$donaturid)->count();

        $donatursantri=function($query) use ($donaturid){
            $query->where('id',$donaturid);
        };
        $santriids=Santri::whereHas('donatur',$donatursantri)->pluck('id')->toArray();
        $bimbinganids=Bimbingan::whereIn('santri_id',$santriids)->pluck('id')->toArray();
        $jmlsantriselesai=Bimbingan::whereIn('santri_id',$santriids)->where('bimbingan_status','2')->count();

        $jmlmateri=Materi::where('materi_status','1')->count()*$jmltersalurkan;        
        $materiselesai=BimbinganMateri::whereIn('bimbingan_id',$bimbinganids)->count();
        if($materiselesai==0){
            $progresbelajar=0;
        }else{
            $progresbelajar=$materiselesai/$jmlmateri; //perhitungan sepertinya belum sesuai
        }

        $donatur=Donatur::where('id',$donaturid)->first();
        $dashdonatur=['donatur'=> $donatur,
                        'donatur_tanggal' => date("Y-m-d"),
                        'donatur_paket_donasi' => $jmldonasi,
                        'donatur_paket_tersalurkan' => $jmltersalurkan,
                        'donatur_santri_selesai' => $jmlsantriselesai,
                        'bimbingan_santri_progress'=>$progresbelajar];

        return response()->json($dashdonatur,200);
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
