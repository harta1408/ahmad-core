<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Referral;
use App\Models\Donatur;
use App\Models\Santri;
use App\Models\Pendamping;
use Validator;

class ReferralAPI extends Controller
{
    #modul untuk mengirimkan referral beserta beritanya, termasuk menyimpan nomor
    #telepon tujuan referal
    public function referralKirim(Request $request){
        $validator = Validator::make($request->all(), [
            'berita_id' => 'required',
            'referral_id_pengirim' => 'required|string',
            'referral_telepon' => 'required|string', 
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $referral=new Referral;
        $referral->berita_id=$request->get('berita_id');
        $referral->referral_id_pengirim=$request->get('referral_id_pengirim');
        $referral->referral_entitas_pengirim=$request->get('referral_entitas_pengirim');
        $referral->referral_telepon=$request->get('referral_telepon');
        $referral->referral_web_link=$request->get('referral_web_link');
        $referral->referral_status='1';
        $referral->save();

        return response()->json($referral,200);    
    }

    #modul untuk melakukan update minimal pengiriman pada masing masing entitas
    #ketika referral yang dikirimkan di gunakan 
    public function referralUpdateMinimal($referralid,$kode){
        $entitas=substr($referralid,0,1); 

        if($entitas=='1'){ //donatur
            $donatur=Donatur::where('donatur_kode',$referralid)->first();
            $minref=$donatur->donatur_min_referral+1;
            Donatur::where('donatur_kode',$referralid)->update(['donatur_min_referral'=>$minref]);
        }
        if($entitas=='2'){ //santri
            $santri=Santri::where('santri_kode',$referralid)->first();
            $minref=$santri->santri_min_referral+1;
            Santri::where('santri_kode',$referralid)->update(['santri_min_referral'=>$minref]);
        }
        if($entitas=='3'){ //pendamping
            $pendamping=Pendamping::where('pendamping_kode',$referralid)->first();
            $minref=$pendamping->pendamping_min_referral+1;
            Pendamping::where('pendamping_kode',$referralid)->update(['pendamping_min_referral'=>$minref]);
        }
        Referral::where('referral_id_pengirim',$referralid)
            ->update(['referral_entitas_penerima'=>$entitas,
                      'referral_id_penerima' => $kode,
                ]);
    }
    #memberbaharui berita yang ingin di kirimkan kepada calon entitas
    private function referralUpdateIdBerita($id, Request $request){
        $exec=Referral::where('id','=' ,$id)->update(['berita_id'=>$request->get('berita_id')]);
        $referral::where('id',$id)->first();
        return response()->json($referral,200);    
    }
}
