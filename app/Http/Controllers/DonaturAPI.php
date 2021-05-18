<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Donatur;

class DonaturAPI extends Controller
{
    public function getDonaturList(){
        $donatur=Donatur::where('donatur_status','1')->get();
        return response()->json($donatur,200);
    }
    public function getDonaturById($id){
        $donatur=Donatur::where('id',$id)->first();
        return response()->json($donatur,200);
    }
    public function saveDonatur(Request $request){
        $donatur=new Donatur; 
        $donatur->donatur_ktp=$request->get('donatur_ktp'); 
        $donatur->donatur_nama=$request->get('donatur_nama'); 
        $donatur->donatur_mobile_no=$request->get('donatur_mobile_no'); 
        $donatur->donatur_email=$request->get('donatur_email'); 
        $donatur->donatur_photo=$request->get('donatur_photo'); 
        $donatur->donatur_kerja=$request->get('donatur_kerja');
        $donatur->donatur_alamat=$request->get('donatur_alamat'); 
        $donatur->donatur_status=$request->get('donatur_status');
        $donatur->save();
        return response()->json($donatur,200);
    }
}
