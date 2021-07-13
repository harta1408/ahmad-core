<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pendamping;
use App\Models\User;
use App\Http\Controllers\ReferralAPI;
use Config;
use Validator;
class PendampingAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
	}
    public function pendampingRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users|max:100',
            'name' => ['required','string','max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        #buat password acak untuk default yang harus langsung diganti
        #ketika email tervirifikasi
        #link verifikasi di panggil berdasarkan user dan password
        $useremail=$request->get('email'); 
        $username=$request->get('name');
        $hashcode=md5(rand(100000,999999)); 
        $usertipe="3"; //tipe user pendamping

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

        #simpan data registrasi pendamping
        $pendampingkode=$this->pendampingKode();
        $pendamping=new Pendamping;
        $pendamping->pendamping_kode=$pendampingkode;
        $pendamping->pendamping_email=$useremail; 
        $pendamping->pendamping_nama=$username;
        $pendamping->pendamping_status='1'; //aktif belum lengkap 
        $pendamping->save();

        #ambil user berdasarkan email
        $user=User::with('pendamping')->where('email',$useremail)->first();

        $msg=new MessageService;

        #kirim email verifikasi
        $msg->kirimEmailVerifikasi($useremail,$username,$hashcode);
        #simpan/kirim pesan
        $msg->simpanNotifikasiSelamatBergabung('0',$user->id);
        return response()->json($user,200);   
    }
    public function pendampingRegisterReferral(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users|max:100',
            'name' => ['required','string','max:50'],
            'referral_id'=> ['required','string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        #buat password acak untuk default yang harus langsung diganti
        #ketika email tervirifikasi
        #link verifikasi di panggil berdasarkan user dan password
        $useremail=$request->get('email'); 
        $username=$request->get('name');
        $referralid=$request->get('referral_id'); 
        $hashcode=md5(rand(100000,999999)); 
        $usertipe="3"; //tipe user pendamping

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

        #simpan data registrasi pendamping
        $pendampingkode=$this->pendampingKode();
        $pendamping=new Pendamping;
        $pendamping->pendamping_kode=$pendampingkode;
        $pendamping->pendamping_email=$useremail; 
        $pendamping->pendamping_nama=$username;
        $pendamping->pendamping_status='1'; //aktif data belum lengkap 
        $pendamping->save();

        $refAPI=new ReferralAPI;
        $refAPI->referralUpdateMinimal($referralid,$santrikode);

        #ambil user berdasarkan email
        $user=User::with('pendamping')->where('email',$useremail)->first();

        $msg=new MessageService;

        #kirim email verifikasi
        $msg->kirimEmailVerifikasi($useremail,$username,$hashcode);
        #simpan/kirim pesan
        $msg->simpanNotifikasiSelamatBergabung('0',$user->id);
        return response()->json($user,200);  
    }
    public function pendampingRegisterGMail(Request $request){
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
        $useremail=$request->get('user_email'); 
        $username=$request->get('user_name');
        $usertipe="3"; //tipe user pendamping
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

        //simpan data registrasi pendamping
        $pendamping=new Pendamping;
        $pendamping->pendamping_kode=$this->pendampingKode();
        $pendamping->pendamping_email=$useremail; 
        $pendamping->pendamping_nama=$username;
        $pendamping->pendamping_status='1'; //aktif belum terpilih 
        $pendamping->save();

        #ambil user berdasarkan email
        $user=User::with('pendamping')->where('email',$useremail)->first();

        $msg=new MessageService;

        #kirim email verifikasi
        $msg->kirimEmailVerifikasi($useremail,$username,$hashcode);
        #simpan/kirim pesan
        $msg->simpanNotifikasiSelamatBergabung('0',$user->id);
        return response()->json($user,200);  
    }
    public function pendampingUploadImage(Request $request){

        //ambil id donatur, kemudian cari di database kodenya
        $id=$request->get('id');  
        $pendamping_kode=Pendamping::where('id',$id)->first()->pendamping_kode;

        $this->validate($request, [
          'pendamping_photo' => 'required | image | mimes:jpeg,png,jpg,gif | max:256'
        ]);
    
        // menyimpan data file yang diupload ke variabel $file
        $images = $request->file('pendamping_photo');
        $new_name=$pendamping_kode.'.'.$images->getClientOriginalExtension();

        //tujuan penyimpanan file
        $tujuan_upload = base_path("images");
        $images->move($tujuan_upload,$new_name); 

        $fileloc=substr($request->root(),0,strlen($request->root())-6) ."images/".$new_name;

        Pendamping::where('pendamping_kode','=',$pendamping_kode)->update(['pendamping_lokasi_photo'=>$fileloc]);

        $pendamping=Pendamping::where('id',$id)->first();
        return response()->json($pendamping,200);
    }
    public function pendampingById($id){
        $pendamping=Pendamping::where('id',$id)->first();
        return response()->json($pendamping,200);
    }
    public function pendampingSantriById($id){
        $santri=function ($query) {
            $query->with('kirimproduk');
        };
        $pendamping=Pendamping::with(['santri'=>$santri])->whereHas('santri',$santri)->where('id',$id)->get();
        return response()->json($pendamping,200);
    }
    public function pendampingNilaiSantri(Request $request){
        $santriid=$request->get('santri_id');
        $pendampingid=$request->get('pendamping_id');
        $materiid=$request->get('materi_id');
        $materinilaiangka=$request->get('materi_nilai_angka');
        $materinilaihuruf=$request->get('materi_nilai_huruf');
        $matericatatan=$request->get('materi_catatan_nilai');

        $soal=soal::where('id',$soalid)->first();
        $soal->santri()->attach([
            'soal_id'=>$soalid],
            [
                'santri_id'=>$santriid, 
                'soal_jawaban_essay'=>$jawaban,
                'soal_jawaban_pilihan' =>$jawaban,
                'soal_nilai'=>$nilai,
        ]);

 
        return response()->json($santri,200);
    }
    public function pendampingKode()
    {
      //otomatis pengaturan kode pendamping dengan format 
      //tahun[2]+bulan[2]+nomor urut[4]
      $bulan=date("m");
      $tahun=date("y");
      $usertipe="3"; //tipe user pendamping
      $strNewId = $usertipe.$tahun.$bulan."0001";

      while ($this->findpendampingKode($strNewId)) { 
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
    private function findpendampingKode($pendampingkode){
        $pendamping=Pendamping::where('pendamping_kode',$pendampingkode)->first();
        if($pendamping){
          return true;
        }
        return false;
    }
}
