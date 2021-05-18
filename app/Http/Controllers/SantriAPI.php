<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Santri;
use App\Models\User;
use Validator;

class SantriAPI extends Controller
{
    //modul pendaftaran santri, menanyakan informasi spesifik saja
    //untuk mempermudah ketika pendaftaran
    public function registerSantri(Request $request){

        //parameter yang dikirim
        $useremail=$request->get('user_email');
        $username=$request->get('user_name');
        $password=Hash::make($request->get('user_password'));


        $usertipe="4"; //santri

        $validator = Validator::make($request->all(), [
            'user_email' => 'required|email|unique:users|max:100',
            'user_name' => 'required|string',
            'user_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        //buat user baru dengan alamat email yang dimasukan
        $user=new User;
        $user->user_email=$useremail;
        $user->user_password=$password;
        $user->user_name=$username;
        $user->user_tipe=$usertipe;
        $exec=$user->save();

        if(!$exec){
            return response()->json(['status' => 'error', 'message' => "Data Cannot be Save", 'code' => 404]);
        }

        //simpan data registrasi santri
        $santri=new Santri;
        $santri->santri_email=$useremail; 
        $santri->santri_nama=$username;
        $santri->santri_status='1'; //aktif belum terpilih 
        $santri->save();
        return response()->json($santri,200);
    }

    public function getSantriByEmail($email){
        $santri=Santri::where('santri_email',$email)->first();
        return response()->json($santri,200);
    }

    public function getSantriList(){
        $santri=Santri::where('santri_status','1')->get();
        return response()->json($santri,200);
    }
    public function getSantriById($id){
        $santri=Santri::where('id',$id)->first();
        return response()->json($santri,200);
    }
  
    public function saveSantri(Request $request){
        $santri=new Santri;
        $santri->santri_kode=$request->get('santri_kode');  
        $santri->santri_nama=$request->get('santri_nama');
        $santri->santri_tmp_lahir=$request->get('santri_tmp_lahir');  
        $santri->santri_tgl_lahir=$request->get('santri_tgl_lahir');   
        $santri->santri_mobile_no=$request->get('santri_mobile_no'); 
        $santri->santri_email=$request->get('santri_email'); 
        $santri->santri_alamat=$request->get('santri_alamat'); 
        $santri->santri_kode_pos=$request->get('santri_kode_pos'); 
        $santri->santri_kelurahan=$request->get('santri_kelurahan'); 
        $santri->santri_kota=$request->get('santri_kota'); 
        $santri->santri_kecamatan=$request->get('santri_kecamatan'); 
        $santri->santri_provinsi=$request->get('santri_provinsi'); 
        $santri->save();

        return response()->json($santri,200);

    }
}
