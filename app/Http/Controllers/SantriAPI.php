<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Santri;
use App\Models\User;
use Validator;

class SantriAPI extends Controller
{
    #modul pendaftaran santri, menanyakan informasi spesifik saja
    #untuk mempermudah ketika pendaftaran
    #pendaftaran pertama hanya memasukan alamat email, password sementara akan
    #dibuat secara otomatis oleh sistem
    public function santriRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'user_email' => 'required|email|unique:users|max:100',
            'user_name' => ['required','string','max:30'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        #buat hash code acak untuk default yang harus langsung diganti
        #ketika email tervirifikasi
        #link verifikasi di panggil berdasarkan user dan hash code
        $useremail=$request->get('user_email'); 
        $username=$request->get('user_name');
        $hashcode=Hash::make(md5(rand(0,1000))); 

        $usertipe="2"; //tipe user santri


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

        #simpan data registrasi santri
        $santri=new Santri;
        $santri->santri_kode=$this->santriKode();
        $santri->santri_email=$useremail; 
        $santri->santri_nama=$username;
        $santri->santri_status='1'; //aktif belum terpilih 
        $santri->save();

        $user=User::with('santri')->where('user_email',$useremail)->first();
        return response()->json($user,200);
    }
    #modul pendaftaran santri melalui account media sosial
    #sepeti gmail, facebook dsb
    public function santriRegisterSosmed(Request $request){
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
 
        $usertipe="2"; //tipe user santri

        #buat user baru dengan alamat email yang dimasukan
        $user=new User;
        $user->user_email=$useremail;
        $user->user_password=$password;
        $user->user_nama=$username;
        $user->user_tipe=$usertipe;
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

        $user=User::with('santri')->where('user_email',$useremail)->first();
        return response()->json($user,200);
    }
  
    public function santriUpdateProfile($id,Request $request){
        $exec=Santri::where('id','=' ,$id)
        ->update(['santri_id'=>$request->get('santri_id'),
                  'santri_nama'=>$request->get('santri_nama'),
                  'santri_tmp_lahir'=>$request->get('santri_tmp_lahir'),
                  'santri_tgl_lahir'=>$request->get('santri_tgl_lahir'),
                  'santri_mobile_no'=>$request->get('santri_mobile_no'),
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
                  ]);
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
