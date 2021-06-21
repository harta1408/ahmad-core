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
            
            $berita=Berita::where('berita_entitas','1')->latest('created_at')->first();
            $referral->berita_id=$berita->id;
            $referral->referral_entitas_kode=$donatur->donatur_kode;
            $referral->referral_nama=$donatur->donatur_nama;
        }
        if($jenis=='SANTRI'){
            $santri=Santri::where('id',$id)->get();
            $berita=Berita::where('berita_entitas','2')->latest('created_at')->first();
            $referral->berita_id=$berita->id;
            $referral->referral_entitas_kode=$santri->santri_kode;
        }
        if($jenis=='PENDAMPING'){
            $pendamping=Pendamping::where('id',$id)->get();
            $berita=Berita::where('berita_entitas','3')->latest('created_at')->first();
            $referral->berita_id=$berita->id;
            $referral->referral_entitas_kode=$pendamping->pendamping_kode;
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

        $validator = Validator::make($request->all(), [
            'referral_telepon' => 'required|string', 
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $refpone=$request->get('referral_telepon');
        $refid=$request->get('referral_entitas_kode');
        $berita_id=$request->get('berita_id');
        $url=' http://kidswa.web.id/ahmad/gabung/donatur/'.$refid;


        $berita=Berita::where('id',$berita_id)->first();


        $pesan=$berita->berita_judul.$url;

        $requestsendmessage=array();
        $requestsendmessage[]= array('NOMOR_TUJUAN' => $refpone,
            'PESAN'=>$pesan
        );

        $messageapi=new MessageAPI;
        $status=$messageapi->processWhatsappMessage($refpone,$pesan);

        dd($status);

        // 'berita_id', //id berita
        // 'referral_id_pengirim', //id pengirim (kode)
        // 'referral_id_penerima', //id penerima (kode) di isi pada saat sudah register
        // 'referral_entitas_pengirim',  //jenis pengirim
        // 'referral_entitas_penerima', //jenis penerima
        // 'referral_telepon', //nomor telepon yang di tuju
        // 'referral_web_link', //link referral yang dikirim
        // 'referral_status',
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

    public function newmenuindex(){
        return view('referral/referralnewmain');
    }
    public function newmenu(Request $request){
        $entitas=$request->get('pilihan');
        if($entitas=='Donatur'){
            $donatur=Donatur::where('donatur_status','1')->get();
            return view('referral/referraldonatur',compact('donatur'));
        }
        if($entitas=='Santri'){
            $santri=Santri::where('santri_status','1')->get();
        }
        if($entitas=='Pendamping'){
            $pendamping=Pendamping::where('pendamping_status')->get(); 
        }
    }
}
