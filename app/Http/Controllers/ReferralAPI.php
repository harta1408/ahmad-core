<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Referral;
use App\Models\Berita;
use App\Models\Donatur;
use App\Models\Santri;
use App\Models\Pendamping;
use App\Http\Controllers\Service\MessageService;
use Validator;
use Config;

class ReferralAPI extends Controller
{
    #modul untuk mendapatkan pesan yang akan dikirim sebagai referral
    #page yang di share sama, oleh karena itu pake metode post, yang mengirimkan
    #id pengirim untuk di catat sistem sebagai pemberi referral
    public function __construct()
    {
        $this->middleware('cors');
	}
    public function referralSendLink(Request $request){
        $url = Config::get('ahmad.referral.development');

        $validator = Validator::make($request->all(), [
            'referral_entitas_kode' => 'required|string|',
            'referral_telepon' => 'required|string|',
            'referral_entitas_tujuan' => 'required|string|',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $kode_entitas=$request->get('referral_entitas_kode'); //kode pengirim
        $nomor_tujuan=$request->get('referral_telepon'); //telepon tujuan
        $entitas_tujuan=$request->get('referral_entitas_tujuan'); //tujuan entitas kirim
        $berita=Berita::where([['berita_jenis','3'],['berita_entitas',$entitas_tujuan]])->first();

        $refpone=$request->get('referral_telepon');
        $refenkode=$request->get('referral_entitas_kode');
        $berita_id=$request->get('berita_id');
        $url=$url.$kode_entitas;
        
        $pesan=$berita->berita_judul." ".$url;
        $requestsendmessage=array();
        $requestsendmessage[]= array('NOMOR_TUJUAN' => $nomor_tujuan,
            'PESAN'=>$pesan
        );
        $messageService=new MessageService;
        $status=$messageService->processWhatsappMessage($refpone,$pesan);
        if($status='Success'){
            $referral=new Referral;
            $referral->referral_entitas_kode=$refenkode;
            $referral->referral_telepon=$refpone;
            $referral->berita_id=$berita->id;
            $referral->referral_web_link=$url;
            $referral->referral_status='1';
            $referral->save();
        }
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
        // Referral::where('referral_id',$referralid)
        //     ->update(['referral_entitas_penerima'=>$entitas,
        //               'referral_id_penerima' => $kode,
        //         ]);
    }
    #memberbaharui berita yang ingin di kirimkan kepada calon entitas
    private function referralUpdateIdBerita($id, Request $request){
        $exec=Referral::where('id','=' ,$id)->update(['berita_id'=>$request->get('berita_id')]);
        $referral::where('id',$id)->first();
        return response()->json($referral,200);    
    }
}
