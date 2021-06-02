<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Referral;
use App\Models\Berita;
use App\Models\Donatur;
use App\Models\Santri;
use App\Models\Pendamping;
use Validator;

class ReferralAPI extends Controller
{
    #modul untuk mendapatkan pesan yang akan dikirim sebagai referral
    #page yang di share sama, oleh karena itu pake metode post, yang mengirimkan
    #id pengirim untuk di catat sistem sebagai pemberi referral
    public function referralWebLink(Request $request){
        $berita=Berita::where('berita_jenis','3')->first();
        $referral=$berita->berita_isi." ".$berita->berita_web_link."register";
        $idpengirim=$request->get('referral_id_pengirim');
        if(!$idpengirim){
            $referral=$berita->berita_isi." ".$berita->berita_web_link;
        }
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
