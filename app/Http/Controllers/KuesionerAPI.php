<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kuesioner;
use App\Models\Santri;

class KuesionerAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
	}
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
        $santriid=$request->get('santri_id');
        $santri=Santri::where('id',$santriid)->first();
        for ($i=0; $i < count($request->input('kuesioner')) ; $i++) {
            $kuesionerid=$request->input('kuesioner')[$i]['kuesioner_id'];
            $kuesioner=Kuesioner::where('id',$kuesionerid)->first();
            $kuesionerjawab=$request->input('kuesioner')[$i]['kuesioner_jawab'];
            $nilai=0;
            if($kuesionerjawab=="YA"){
                $nilai=$kuesioner->kuesioner_bobot_yes;
            }else{
                $nilai=$kuesioner->kuesioner_bobot_no;
            }
            $kuesioner->santri()->attach([
                'kuesioner_id'=>$kuesionerid],
                [
                    'santri_id'=>$santriid, 
                    'kuesioner_jawab'=>$kuesionerjawab,
                    'kuesioner_nilai'=>$nilai,
                ]);
        }
        $santri=Santri::with('kuesioner')->where('id',$santriid)->first();
        return response()->json($santri,200);
    }
}
