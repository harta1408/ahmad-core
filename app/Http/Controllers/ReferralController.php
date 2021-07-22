<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Referral;
use App\Models\Berita;
use App\Models\Donatur;
use App\Models\Santri;
use App\Models\Pendamping;
use App\Http\Controllers\Service\MessageService;
use App\Http\Controllers\BeritaController;
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
            // $jenisentitas=substr($donatur->donatur_kode,0,1); 
            // $berita=Berita::where([['berita_jenis','3'],['berita_entitas',$jenisentitas]])->latest('created_at')->first();
            // if(!$berita){
            //     return response()->json(['status' => 'error', 'message' => 'Broadcast Donatur tidak ditemukan', 'code' => 404]);
            // }
            // $referral->berita_id=$berita->id;
            $referral->referral_entitas_kode=$donatur->donatur_kode;
            $referral->referral_nama=$donatur->donatur_nama;
        }
        if($jenis=='SANTRI'){
            $santri=Santri::where('id',$id)->first();
            // $jenisentitas=substr($santri->santri_kode,0,1); 
            // $berita=Berita::where([['berita_jenis','3'],['berita_entitas',$jenisentitas]])->latest('created_at')->first();
            // if(!$berita){
            //     return response()->json(['status' => 'error', 'message' => 'Broadcast Santri tidak ditemukan', 'code' => 404]);
            // }
            // $referral->berita_id=$berita->id;
            $referral->referral_entitas_kode=$santri->santri_kode;
            $referral->referral_nama=$santri->santri_nama;
        }
        if($jenis=='PENDAMPING'){
            $pendamping=Pendamping::where('id',$id)->first();
            // $jenisentitas=substr($pendamping->pendamping_kode,0,1); 
            // if(!$berita){
            //     return response()->json(['status' => 'error', 'message' => 'Broadcast Pendamping tidak ditemukan', 'code' => 404]);
            // }
            // $referral->berita_id=$berita->id;
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
        $referral=Referral::where('referral_status','1')->orderBy('created_at','desc')->get();
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
        $url = Config::get('ahmad.referral.development'); 

        if (!isset($request->get('referral')['referral_telepon'])) {
            return response()->json(['status' => 'error', 'message' => 'Telepon Harus di Isi', 'code' => 404]);
        }
        if (!isset($request->get('referral')['referral_entitas_tujuan'])) {
            return response()->json(['status' => 'error', 'message' => 'Pilih Entitas Tujuan', 'code' => 404]);
        }
        $refpone=$request->get('referral')['referral_telepon'];
        $refenkode=$request->get('referral')['referral_entitas_kode'];
        $jenisentitas=$request->get('referral')['referral_entitas_tujuan'];

        // if($jenisentitas=='1'){
        //     $url=$urldonatur.$refenkode;
        // }
        // if($jenisentitas=='2'){
        //     $url=$urlsantri.$refenkode;
        // }
        // if($jenisentitas=='3'){
        //     $url=$urlpendamping.$refenkode;
        // }

        $berita=Berita::where([['berita_jenis','3'],['berita_entitas',$jenisentitas]])->latest('created_at')->first();
        $pesan=$berita->berita_judul." ".$url.$refenkode;

        $requestsendmessage=array();
        $requestsendmessage[]= array('NOMOR_TUJUAN' => $refpone,
            'PESAN'=>$pesan
        );

        $messageService=new MessageService;
        $status=$messageService->processWhatsappMessage($refpone,$pesan);

        // dd($status);
        $pesan=$status;
        if($status='Success'){
            $pesan='Pesan Terkirim';
            $referral=new Referral;
            $referral->referral_entitas_kode=$refenkode;
            $referral->referral_telepon=$refpone;
            $referral->berita_id=$berita->id;
            $referral->referral_web_link=$url.$refenkode;
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

    #modul untuk mengubah isi berita yang dikirimkan ke donatur, santri atau pendamping
    #walaupun ada di referral, namun penyimpanan data pada tabel berita
    public function referralKontenIndex(){
        return view('referral/referralkontenindex');
    }
    public function referralKontenMain(Request $request){
        $entitas=$request->get('pilihan');
        $beritacontrol=new BeritaController;
        $berita=$beritacontrol->panggilBeritaBroadcast($entitas);

        return view('referral/referralkontenupdate',compact('berita'));
    }
    public function referralKontenUpdate(Request $request, $id){
        $res = Berita::where('id', $id)->update($request->except(['id','_token','_method']));
        if (!$res) {
            return response()->json(['status' => 'error', 'message' => 'System Error', 'code' => 404]);
        }
        return redirect()->action('HomeController@index');
    }
}
