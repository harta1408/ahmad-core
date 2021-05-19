<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kuesioner;

class KuesionerAPI extends Controller
{
    public function kuesionerSimpan(Request $request){
        $kuesioner=new Kuesioner;
        $kuesioner->kuesioner_tanya=$request->get('kuesioner_tanya');
        $kuesioner->kuesioner_bobot_yes=$request->get('kuesioner_bobot_yes');
        $kuesioner->kuesioner_bobot_no=$request->get('kuesioner_bobot_no');
        $kuesioner->kuesioner_status='1';
        $kuesioner->save();
        return response()->json($kuesioner,200);

    }
    public function kuesionerList(){
        $kuesioner=Kuesioner::where('kuesioner_status','1')->get();
        return response()->json($kuesioner,200);
    }
    public function kuesionerSantriSimpan(Request $request){
        
    }
}
