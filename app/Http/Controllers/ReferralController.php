<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Referral;
use App\Models\Berita;
use App\Models\Donatur;
use App\Models\Santri;
use App\Models\Pendamping;
use App\Http\Controllers\MessageAPI;
use Config;
use Validator;

class ReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('cors');
	}
    public function index()
    {
        
        return view('referral/referralindex');
    }

    public function main(Request $request){
        $id=$request->get('id_entitas');
        $jenis=$request->get("jenis_entitas");

        $referral=new Referral;
        if($jenis=="DONATUR"){
            $donatur=Donatur::where('id',$id)->first();
            $jenisentitas=substr($donatur->donatur_kode,0,1); 
            $berita=Berita::where([['berita_jenis','3'],['berita_entitas',$jenisentitas]])->latest('created_at')->first();
            if(!$berita){
                return false;
            }
            $referral->berita_id=$berita->id;
            $referral->referral_entitas_kode=$donatur->donatur_kode;
            $referral->referral_nama=$donatur->donatur_nama;
        }
        if($jenis=='SANTRI'){
            $santri=Santri::where('id',$id)->first();
            $jenisentitas=substr($santri->santri_kode,0,1); 
            $berita=Berita::where([['berita_jenis','3'],['berita_entitas',$jenisentitas]])->latest('created_at')->first();
            $referral->berita_id=$berita->id;
            $referral->referral_entitas_kode=$santri->santri_kode;
            $referral->referral_nama=$santri->santri_nama;
        }
        if($jenis=='PENDAMPING'){
            $pendamping=Pendamping::where('id',$id)->first();
            $jenisentitas=substr($pendamping->pendamping_kode,0,1); 
            $berita=Berita::where([['berita_jenis','3'],['berita_entitas',$jenisentitas]])->latest('created_at')->first();
            $referral->berita_id=$berita->id;
            $referral->referral_entitas_kode=$pendamping->pendamping_kode;
            $referral->referral_nama=$pendamping->pendamping_nama;
        }
        return view("referral/referralnew",compact('referral'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $referral=Referral::where('referral_status','1')->get();
        return $referral;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $urldonatur = Config::get('ahmad.referral.development.donatur');
        $urlsantri = Config::get('ahmad.referral.development.santri');
        $urlpendamping = Config::get('ahmad.referral.development.pendamping');

        if (!isset($request->get('referral')['referral_telepon'])) {
            return response()->json(['status' => 'error', 'message' => 'Telepon Harus di Isi', 'code' => 404]);
        }
        $refpone=$request->get('referral')['referral_telepon'];
        $refenkode=$request->get('referral')['referral_entitas_kode'];
        $berita_id=$request->get('referral')['berita_id'];
        $jenisentitas=substr($refenkode,0,1); 
        if($jenisentitas=='1'){
            
            $url=$urldonatur.$refenkode;
        }
        if($jenisentitas=='2'){
            $url=$urlsantri.$refenkode;
        }
        if($jenisentitas=='3'){
            $url=$urlpendamping.$refenkode;
        }

        $berita=Berita::where('id',$berita_id)->first();
        $pesan=$berita->berita_judul." ".$url;

        $requestsendmessage=array();
        $requestsendmessage[]= array('NOMOR_TUJUAN' => $refpone,
            'PESAN'=>$pesan
        );

        $messageapi=new MessageAPI;
        $status=$messageapi->processWhatsappMessage($refpone,$pesan);

        // dd($status);
        $pesan=$status;
        if($status='Success'){
            $pesan='Pesan Terkirim';
            $referral=new Referral;
            $referral->referral_entitas_kode=$refenkode;
            $referral->referral_telepon=$refpone;
            $referral->berita_id=$berita_id;
            $referral->referral_web_link=$url;
            $referral->referral_status='1';
            $referral->save();
        }

        return response()->json(['status' => 'success', 'message' => $pesan, 'code' => 200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function newmenuindex(Request $request){
        return view('referral/referralnewmenu');
    }
    
    public function newmenu(Request $request){
        $entitas=$request->get('pilihan');
        if($entitas=='Donatur'){
            $donatur=Donatur::where('donatur_status','1')->get();
            return view('referral/referraldonatur');
        }
        if($entitas=='Santri'){
            $santri=Santri::where('santri_status','1')->get();
            return view('referral/referralsantri');
        }
        if($entitas=='Pendamping'){
            $pendamping=Pendamping::where('pendamping_status')->get(); 
            return view('referral/referralpendamping');
        }
    }
}
