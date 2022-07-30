<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kuesioner;
use App\Models\Santri;
use App\Models\Pendamping;

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
    public function kuesionerUpdate($id, Request $request){
        Kuesioner::where('id',$id)
            ->update(['kuesioner_tanya'=>$request->get('kuesioner_tanya'),
                      'kuesioner_bobot_yes'=>$request->get('kuesioner_bobot_yes'),
                      'kuesioner_bobot_no'=>$request->get('kuesioner_bobot_no'),
            ]);
        $kuesioner=Kuesioner::where('id',$id)->first();
        return response()->json($kuesioner,200);

    }
    public function kuesionerList(){
        $kuesioner=Kuesioner::where('kuesioner_status','1')->get();
        return response()->json($kuesioner,200);
    }
    public function kuesionerByEntitas($entitas){
        $kuesioner=Kuesioner::where([['kuesioner_status','1'],['kuesioner_entitas',$entitas]])->get();
        return response()->json($kuesioner,200);
    }
    public function kuesionerSantriSimpan(Request $request){
        $santriid=$request->get('santri_id');
        $santri=Santri::where('id',$santriid)->first();
        if(!$santri){
            return response()->json(['status' => 'error', 'message' => 'Data Santri tidak ditemukan', 'code' => 404]);
        }  
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
        Santri::where('id',$santriid)->update(['santri_status'=>'3']); //update sudah jawab kuesioner
        $santri=Santri::with('kuesioner')->where('id',$santriid)->first();
        return response()->json($santri,200);
    }
    public function kuesionerPendampingSimpan(Request $request){
        $pendampingid=$request->get('pendamping_id');
        $pendamping=Pendamping::where('id',$pendampingid)->first();

        if(!$pendamping){
            return response()->json(['status' => 'error', 'message' => 'Data Pendamping tidak ditemukan', 'code' => 404]);
        }        
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
            $kuesioner->pendamping()->attach([
                'kuesioner_id'=>$kuesionerid],
                [
                    'pendamping_id'=>$pendampingid, 
                    'kuesioner_jawab'=>$kuesionerjawab,
                    'kuesioner_nilai'=>$nilai,
                ]);
        }
        Pendamping::where('id',$pendampingid)->update(['pendamping_status'=>'2']); //update sudah jawab kuesioner
        $pendamping=Pendamping::with('kuesioner')->where('id',$pendampingid)->first();
        return response()->json($pendamping,200);
    }
}
