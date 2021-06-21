<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Referral;
use App\Models\Berita;
use App\Models\Donatur;
use App\Models\Santri;
use App\Models\Pendamping;
use App\Http\Controllers\MessageAPI;
use Validator;

class ReferralAPI extends Controller
{
    #modul untuk mendapatkan pesan yang akan dikirim sebagai referral
    #page yang di share sama, oleh karena itu pake metode post, yang mengirimkan
    #id pengirim untuk di catat sistem sebagai pemberi referral
    public function referralSendLink(Request $request){

        $kode_entitas=$request->get('referral_entitas_kode'); //kode pengirim
        $jenisentitas=substr($kode_entitas,0,1); 
        $nomor_tujuan=$request->get('referral_telepon'); //telepon tujuan
        $berita=Berita::where([['berita_jenis','3'],['berita_entitas',$jenisentitas]])->first();


        $refpone=$request->get('referral_telepon');
        $refid=$request->get('referral_entitas_kode');
        $berita_id=$request->get('berita_id');
        if($jenisentitas=='1'){
            $url=' http://kidswa.web.id/ahmad/gabung/donatur/'.$kode_entitas;
        }
        if($jenisentitas=='2'){
            $url=' http://kidswa.web.id/ahmad/gabung/santri/'.$kode_entitas;
        }
        if($jenisentitas=='3'){
            $url=' http://kidswa.web.id/ahmad/gabung/pendamping/'.$kode_entitas;
        }
        
        $pesan=$berita->berita_judul.$url;
        $requestsendmessage=array();
        $requestsendmessage[]= array('NOMOR_TUJUAN' => $nomor_tujuan,
            'PESAN'=>$pesan
        );
        $messageapi=new MessageAPI;
        $status=$messageapi->processWhatsappMessage($refpone,$pesan);

        return response()->json($status,200);    
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
