<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Santri;

class SantriAPI extends Controller
{
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
