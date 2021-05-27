<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Donatur;
use App\Models\User;
use Validator;


class DonaturAPI extends Controller
{
    #register donatur dengan email pribadi hanya menanyakan alamat email
    #sistem mengirimkan email verifikasi untuk penggantian password
    public function donaturRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'user_email' => 'required|email|unique:users|max:100',
            'user_name' => ['required','string','max:30'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        #buat password acak untuk default yang harus langsung diganti
        #ketika email tervirifikasi
        #link verifikasi di panggil berdasarkan user, nama dan password
        $useremail=$request->get('user_email'); 
        $username=$request->get('user_name');
        // $hashcode=Hash::make(rand(0,1000)); 
        $hashcode=Hash::make(md5(rand(0,1000))); 


        $usertipe="1"; //tipe user donatur


        #buat user baru dengan alamat email yang dimasukan
        $user=new User;
        $user->user_email=$useremail;
        $user->user_name=$username;
        $user->user_hash_code=$hashcode; 
        $exec=$user->save();

        if(!$exec){
            return response()->json(['status' => 'error', 'message' => "Data Cannot be Save", 'code' => 404]);
        }

        #simpan data registrasi donatur
        $donatur=new Donatur;
        $donatur->donatur_kode=$this->donaturKode();
        $donatur->donatur_email=$useremail; 
        $donatur->donatur_nama=$username;
        $donatur->donatur_status='1'; //aktif belum terpilih 
        $donatur->save();

        $user=User::with('donatur')->where('user_email',$useremail)->first();
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
        $donatur->donatur_status='1'; //aktif belum terpilih 
        $donatur->save();

        $user=User::with('donatur')->where('user_email',$useremail)->first();
        return response()->json($user,200);    
    }

    #memperbaharui profile donatur
    public function donaturUpdateProfile($id,Request $request){
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
