<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Santri;
use App\Models\Donatur;
use App\Models\Pendamping;
use App\Models\User;
use App\Models\Materi;
use App\Models\KirimProduk;
use App\Models\Bimbingan;
use App\Models\BimbinganMateri;
use App\Models\Lembaga;
use App\Http\Controllers\ReferralAPI;
use App\Http\Controllers\Service\MessageService;
use GeniusTS\HijriDate\Date;
use GeniusTS\HijriDate\Hijri;
use GeniusTS\HijriDate\Translations\Indonesian;
use Config;
use Validator;

class SantriAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
	}
    #modul pendaftaran santri, menanyakan informasi spesifik saja
    #untuk mempermudah ketika pendaftaran
    #pendaftaran pertama hanya memasukan alamat email, password sementara akan
    #dibuat secara otomatis oleh sistem
    public function santriRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users|max:100',
            'name' => ['required','string','max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        #buat hash code acak untuk default yang harus langsung diganti
        #ketika email tervirifikasi
        #link verifikasi di panggil berdasarkan user dan hash code
        $useremail=$request->get('email'); 
        $username=$request->get('name');
        $usertipe="2"; //tipe user santri
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

        #simpan data registrasi santri
        $santrikode=$this->santriKode();
        $santri=new Santri;
        $santri->santri_kode=$santrikode;
        $santri->santri_email=$useremail; 
        $santri->santri_nama=$username;
        $santri->santri_status='1'; //aktif belum di otorisasi
        $santri->save();

        #ambil user berdasarkan email
        $user=User::with('santri')->where('email',$useremail)->first();

        $msg=new MessageService;

        #kirim email verifikasi
        $msg->kirimEmailVerifikasi($useremail,$username,$hashcode);
        #simpan/kirim pesan
        $msg->simpanNotifikasiSelamatBergabung('0',$user->id);
        return response()->json($user,200);  
    }

    public function santriRegisterReferral(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users|max:100',
            'name' => ['required','string','max:50'],
            'referral_id'=> ['required','string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        #buat hash code acak untuk default yang harus langsung diganti
        #ketika email tervirifikasi
        #link verifikasi di panggil berdasarkan user dan hash code
        $useremail=$request->get('email'); 
        $username=$request->get('name');
        $referralid=$request->get('referral_id'); 
        $usertipe="2"; //tipe user santri
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

        #simpan data registrasi santri
        $santrikode=$this->santriKode();
        $santri=new Santri;
        $santri->santri_kode=$santrikode;
        $santri->santri_email=$useremail; 
        $santri->santri_nama=$username;
        $santri->santri_status='1'; //aktif belum di otorisasi
        $santri->save();

        $refAPI=new ReferralAPI;
        $refAPI->referralUpdateMinimal($referralid,$santrikode);

        #ambil user berdasarkan email
        $user=User::with('santri')->where('email',$useremail)->first();

        $msg=new MessageService;

        #kirim email verifikasi
        $msg->kirimEmailVerifikasi($useremail,$username,$hashcode);
        #simpan/kirim pesan
        $msg->simpanNotifikasiSelamatBergabung('0',$user->id);
        return response()->json($user,200);  
    }


    #modul pendaftaran santri melalui account media sosial
    #sepeti gmail, facebook dsb
    public function santriRegisterGMail(Request $request){
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
        $usertipe="2"; //tipe user santri        
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

        //simpan data registrasi santri
        $santri=new Santri;
        $santri->santri_kode=$this->santriKode();
        $santri->santri_email=$useremail; 
        $santri->santri_nama=$username;
        $santri->santri_status='1'; //aktif belum data belum lengkap 
        $santri->save();

        #ambil user berdasarkan email
        $user=User::with('santri')->where('email',$useremail)->first();

        $msg=new MessageService;

        #kirim email verifikasi
        $msg->kirimEmailVerifikasi($useremail,$username,$hashcode);
        #simpan/kirim pesan
        $msg->simpanNotifikasiSelamatBergabung('0',$user->id);
        return response()->json($user,200);  
    }
    

    public function santriUpdateProfile($id,Request $request){
        $validator = Validator::make($request->all(), [
            'santri_nama' => 'required|string',
            'santri_telepon' => 'required|string',
            'santri_alamat' => 'required|string',
            'santri_kecamatan_id' => 'required|string',
            'santri_kota_id' => 'required|string',
            'santri_provinsi_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $exec=Santri::where('id','=' ,$id)
        ->update(['santri_nid'=>$request->get('santri_nid'),
                  'santri_nama'=>$request->get('santri_nama'),
                  'santri_tmp_lahir'=>$request->get('santri_tmp_lahir'),
                  'santri_tgl_lahir'=>$request->get('santri_tgl_lahir'),
                  'santri_gender'=>$request->get('santri_gender'),
                  'santri_telepon'=>$request->get('santri_telepon'),
                  'santri_kerja'=>$request->get('santri_kerja'),
                  'santri_lokasi_photo'=>$request->get('santri_lokasi_photo'),
                  'santri_alamat'=>$request->get('santri_alamat'),
                  'santri_kode_pos'=>$request->get('santri_kode_pos'),
                  'santri_kelurahan'=>$request->get('santri_kelurahan'),
                  'santri_kecamatan'=>$request->get('santri_kecamatan'),
                  'santri_kota'=>$request->get('santri_kota'),
                  'santri_provinsi'=>$request->get('santri_provinsi'),
                  'santri_kecamatan_id'=>$request->get('santri_kecamatan_id'),
                  'santri_kota_id'=>$request->get('santri_kota_id'),
                  'santri_provinsi_id'=>$request->get('santri_provinsi_id'),
                  'santri_status' => '4',
                  ]);
        if(!$exec){
            return response()->json(['status' => 'error', 'message' => "Data Cannot be Save", 'code' => 404]);
        }
        $santri=Santri::where('id',$id)->first();
        return response()->json($santri,200);
    }

    #mengambil data santri berdasarkan alamat email
    public function santriByEmail($email){
        $santri=Santri::where('santri_email',$email)->first();
        return response()->json($santri,200);
    }

    public function santriList(){
        $santri=Santri::where('santri_status','1')->get();
        return response()->json($santri,200);
    }
    public function santriById($id){
        $santri=Santri::where('id',$id)->first();
        return response()->json($santri,200);
    }
    public function santriLacakProduk($id){
        $kirimproduk=KirimProduk::with('manifest')->where('santri_id',$id)->first();
        return response()->json($kirimproduk,200);
    }
    public function santriUploadImage(Request $request){

        //ambil id donatur, kemudian cari di database kodenya
        $id=$request->get('id');  
        $santri_kode=Santri::where('id',$id)->first()->santri_kode;

        // $this->validate($request, [
        //   'santri_photo' => 'required | image | mimes:jpeg,png,jpg,gif | max:256'
        // ]);
    

        // menyimpan data file yang diupload ke variabel $file
        $images = $request->file('santri_photo');
        $new_name=$santri_kode.'.'.$images->getClientOriginalExtension();

        //tujuan penyimpanan file
        $tujuan_upload = base_path("images");
        $images->move($tujuan_upload,$new_name); 

        $fileloc=substr($request->root(),0,strlen($request->root())-6) ."images/".$new_name;

        Santri::where('santri_kode','=',$santri_kode)->update(['santri_lokasi_photo'=>$fileloc]);

        $santri=Santri::where('id',$id)->first();
        return response()->json($santri,200);
    }
    public function santriDashboard($santriid){
        $jmlmateri=Materi::where('materi_status','1')->count();
        $bimbingan=Bimbingan::where('santri_id',$santriid)->first();
        if(!$bimbingan){
            return response()->json(['status' => 'error', 'message' => 'santri tidak dalam bimbingan', 'code' => 404]);
        }
        $bimbinganid=$bimbingan->id;

        $todaydate=date_create(date("Y-m-d"));
        $bimbinganmulai=date_create($bimbingan->bimbingan_mulai);
        $bimbinganakhir=date_create($bimbingan->bimbingan_berakhir);

        $jangkawaktu=date_diff($bimbinganmulai,$bimbinganakhir)->days;;
        $berjalan=date_diff($todaydate,$bimbinganakhir)->days;
        $sisabulan=date_diff($todaydate,$bimbinganakhir)->m.' bulan';
        
        $materiselesai=BimbinganMateri::where('bimbingan_id',$bimbinganid)->count();

        $progresbelajar=$materiselesai/$jmlmateri;
        $waktubelajar=1-($berjalan/$jangkawaktu);

        $santri=Santri::where('id',$santriid)->first();
        $pendampingnama=Pendamping::where('id',$bimbingan->pendamping_id)->first()->pendamping_nama;
        $santridonatur=function ($query) use ($santriid){
            $query->where('id',$santriid);
        };
        $donaturnama=Donatur::whereHas('santri',$santridonatur)->first()->donatur_nama;

        #tanggal hijriah
        $adjhijr=Lembaga::first()->lembaga_adjust_hijr;
        Hijri::setDefaultAdjustment($adjhijr);
        Date::setTranslation(new Indonesian);
        $today = Date::today();
        $hijriah=$today->format('d F o');

        $dashsantri=['santri'=> $santri,
                     'bimbingan_hari_ini' => date("Y-m-d"),
                     'bimbingan_mulai' => $bimbingan->bimbingan_mulai,
                     'bimbingan_akhir' => $bimbingan->bimbingan_berakhir,
                     'santri_progress_belajar'=>$progresbelajar,
                     'santri_progress_waktu'=>$waktubelajar,
                     'santri_sisa_bulan'=>$sisabulan,
                     'santri_min_referral' => $santri->santri_min_referral,
                     'santri_max_referral' => '7',
                     'santri_hijriah' => $hijriah,
                     'pendamping' => $pendampingnama,
                     'donatur'=>$donaturnama];
                     

        return response()->json($dashsantri,200);
    }
    public function santriDashboardById($santriid){
        $jmlmateri=Materi::where('materi_status','1')->count();
        $bimbingan=Bimbingan::where('santri_id',$santriid)->first();
        if(!$bimbingan){
            return response()->json(['status' => 'error', 'message' => 'santri tidak dalam bimbingan', 'code' => 404]);
        }
        $bimbinganid=$bimbingan->id;

        $todaydate=date_create(date("Y-m-d"));
        $bimbinganmulai=date_create($bimbingan->bimbingan_mulai);
        $bimbinganakhir=date_create($bimbingan->bimbingan_berakhir);

        $jangkawaktu=date_diff($bimbinganmulai,$bimbinganakhir)->days;;
        $berjalan=date_diff($todaydate,$bimbinganakhir)->days;
        $sisabulan=date_diff($todaydate,$bimbinganakhir)->m.' bulan';
        
        $materiselesai=BimbinganMateri::where('bimbingan_id',$bimbinganid)->count();

        $progresbelajar=$materiselesai/$jmlmateri;
        $waktubelajar=1-($berjalan/$jangkawaktu);

        $santri=Santri::where('id',$santriid)->first();
        $pendampingnama=Pendamping::where('id',$bimbingan->pendamping_id)->first()->pendamping_nama;
        $santridonatur=function ($query) use ($santriid){
            $query->where('id',$santriid);
        };
        $donaturnama=Donatur::whereHas('santri',$santridonatur)->first()->donatur_nama;

        #tanggal hijriah
        $adjhijr=Lembaga::first()->lembaga_adjust_hijr;
        Hijri::setDefaultAdjustment($adjhijr);
        Date::setTranslation(new Indonesian);
        $today = Date::today();
        $hijriah=$today->format('d F o');

        $dashsantri=['santri_id'=> $santriid,
                     'bimbingan_hari_ini' => date("Y-m-d"),
                     'bimbingan_mulai' => $bimbingan->bimbingan_mulai,
                     'bimbingan_akhir' => $bimbingan->bimbingan_berakhir,
                     'santri_progress_belajar'=>$progresbelajar,
                     'santri_progress_waktu'=>$waktubelajar,
                     'santri_sisa_bulan'=>$sisabulan,
                     'santri_min_referral' => $santri->santri_min_referral,
                     'santri_max_referral' => '7',
                     'santri_hijriah' => $hijriah,
                     'pendamping' => $pendampingnama,
                     'donatur'=>$donaturnama];
                     

        return $dashsantri;
    }

    public function santriKode()
    {
      //otomatis pengaturan kode santri dengan format 
      //tahun[2]+bulan[2]+nomor urut[4]
      $bulan=date("m");
      $tahun=date("y");
      $usertipe="2"; //tipe user santri
      $strNewId = $usertipe.$tahun.$bulan."0001";

      while ($this->findSantriKode($strNewId)) { 
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
    private function findSantriKode($santrikode){
        $santri=Santri::where('santri_kode',$santrikode)->first();
        if($santri){
          return true;
        }
        return false;
    }

}
