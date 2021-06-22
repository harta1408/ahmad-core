<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\Soal;

class SoalAPI extends Controller
{
    public function santriJawabSoal(Request $request){
        $santriid=$request->get('santri_id');
        $santri=Santri::where('id',$santriid)->first();
        for ($i=0; $i < count($request->input('soal')) ; $i++) {
            $soalid=$request->input('soal')[$i]['soal_id'];
            $soal=soal::where('id',$soalid)->first();
            $jenis=$soal->soal_jenis;
            $jawaban="";
            if($jenis='1'){ //essay
                $jawaban=$request->input('soal')[$i]['soal_jawaban_essay'];
            }else{
                $jawaban=$request->input('soal')[$i]['soal_jawaban_pilihan'];
            }
            $nilai=$jawaban=$request->input('soal')[$i]['soal_nilai'];


            $soaljawab=$request->input('soal')[$i]['soal_jawab'];
            $nilai=0;
            if($soaljawab=="YA"){
                $nilai=$soal->soal_bobot_yes;
            }else{
                $nilai=$soal->soal_bobot_no;
            }
            $soal->santri()->attach([
                'soal_id'=>$soalid],
                [
                    'santri_id'=>$santriid, 
                    'soal_jawaban_essay'=>$jawaban,
                    'soal_jawaban_pilihan' =>$jawaban,
                    'soal_nilai'=>$nilai,
                ]);
        }
        Santri::where('id',$santriid)->update(['santri_status','2']); //update sudah jawab soal
        $santri=Santri::with('soal')->where('id',$santriid)->first();
        return response()->json($santri,200);
    }
}
