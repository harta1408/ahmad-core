<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Pesan;
use App\Models\User;
use App\Models\Donatur;
use App\Models\Santri;
use App\Models\Pendamping;
use Validator;


class PesanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('pesan/pesanindex');
    }
    public function main(Request $request){
        $pesanid=$request->get('pesan_id');
        $pesanstatus=$request->get("pesan_state");


        if($pesanstatus=="NEW"){
            return view("pesan/pesannewmenu");
        }

        $pesan=Pesan::where('id',$pesanid)->first();
        if($pesanstatus=="UPDATE"){
            return view("pesan/pesanupdate",compact('pesan'));
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pesan=Pesan::with('pembuat','tujuan')->where('pesan_status','1')->get();
        foreach ($pesan as $key => $psn) {
            if($psn->pesan_tujuan_entitas=='0'){
                $psn->pesan_tujuan_entitas="Semua";
            }
            if($psn->pesan_tujuan_entitas=='1'){
                $psn->pesan_tujuan_entitas="Donatur";
            }
            if($psn->pesan_tujuan_entitas=='2'){
                $psn->pesan_tujuan_entitas="Santri";
            }
            if($psn->pesan_tujuan_entitas=='3'){
                $psn->pesan_tujuan_entitas="Pendamping";
            }

            if($psn->pesan_status=='1'){
                $psn->pesan_status="UnRead";
            }
            if($psn->pesan_status=='2'){
                $psn->pesan_status="Read";
            }
        }
        return $pesan;
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
            'pesan_isi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        $userid=Auth::user()->id;
        $pesantujuan=$request->get('pesan_tujuan');
        $selectedentitas=explode(",",$request->get('selectedentitas')); 

        #proses pengiriman pesan dengan menyimpan pesan pada tabel relasi dengan user
        #konsep pesan berbeda dengan berita dan hadist, karena pesan di relasikan dengan user
        #bukan dengan entitas
        #cari dulu dari mana asal permintaan



        // dd($selectedentitas);

        $pesantujuanentitas='0';
        if($pesantujuan=='SEMUA'){
            #ambil semua user dengan tipe 1,2 dan 3
            $user=User::whereIn('tipe',[1,2,3])->get();  
            foreach ($user as $key => $usr) {
                $pesan=new Pesan;
                $pesan->pesan_pembuat_id=$userid;
                $pesan->pesan_tujuan_entitas=$pesantujuanentitas; 
                $pesan->pesan_tujuan_id=$usr->id; 
                $pesan->pesan_isi=$request->get('pesan_isi'); 
                $pesan->pesan_waktu_kirim=$request->get('pesan_waktu_kirim'); 
                $pesan->pesan_status='1'; //aktif 
                $exec = $pesan->save();
            }       
        }
        if($pesantujuan=='DONATUR'){
            $pesantujuanentitas='1';
            $donatur=Donatur::whereIn('id',$selectedentitas)->get();
            foreach ($donatur as $key => $dnt) {
                $email=$dnt->donatur_email;
                $selecteduserid=User::where('email',$email)->first()->id;

                $pesan=new Pesan;
                $pesan->pesan_pembuat_id=$userid;
                $pesan->pesan_tujuan_entitas=$pesantujuanentitas; 
                $pesan->pesan_tujuan_id=$selecteduserid; 
                $pesan->pesan_isi=$request->get('pesan_isi'); 
                $pesan->pesan_waktu_kirim=$request->get('pesan_waktu_kirim'); 
                $pesan->pesan_status='1'; //aktif 
                $exec = $pesan->save();
            }
        }
        if($pesantujuan=='SANTRI'){
            $pesantujuanentitas='2';
            $santri=Santri::whereIn('id',$selectedentitas)->get();
            foreach ($santri as $key => $snt) {
                $email=$snt->santri_email;
                $selecteduserid=User::where('email',$email)->first()->id;
                
                $pesan=new Pesan;
                $pesan->pesan_pembuat_id=$userid;
                $pesan->pesan_tujuan_entitas=$pesantujuanentitas; 
                $pesan->pesan_tujuan_id=$selecteduserid; 
                $pesan->pesan_isi=$request->get('pesan_isi'); 
                $pesan->pesan_waktu_kirim=$request->get('pesan_waktu_kirim'); 
                $pesan->pesan_status='1'; //aktif 
                $exec = $pesan->save();
            }
        }
        if($pesantujuan=='PENDAMPING'){
            $pesantujuanentitas='3';
            $pendamping=Pendamping::whereIn('id',$selectedentitas)->get();
            foreach ($pendamping as $key => $pdmp) {
                $email=$pdmp->pendamping_email;
                $selecteduserid=User::where('email',$email)->first()->id;
                
                $pesan=new Pesan;
                $pesan->pesan_pembuat_id=$userid;
                $pesan->pesan_tujuan_entitas=$pesantujuanentitas; 
                $pesan->pesan_tujuan_id=$selecteduserid; 
                $pesan->pesan_isi=$request->get('pesan_isi'); 
                $pesan->pesan_waktu_kirim=$request->get('pesan_waktu_kirim'); 
                $pesan->pesan_status='1'; //aktif 
                $exec = $pesan->save();
            }
        }
        return redirect()->action('PesanController@index');

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
        $res = Pesan::where('id', $id)->update($request->except(['id','_token','_method']));
        if (!$res) {
            return response()->json(['status' => 'error', 'message' => 'System Error', 'code' => 404]);
        }
        return redirect()->action('PesanController@index');
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

    public function newmenu(Request $request){
        $entitas=$request->get('pilihan');
        if($entitas=='Semua'){
            // $arrentitas=json_encode($this->processSendHadist('SEMUA',$hadistid,$entitas));
            // dd($arrentitas);
            $selectedentitas="0";
            $jenisentitas="SEMUA";
            return view('pesan/pesannew',compact('selectedentitas','jenisentitas'));
        }
        if($entitas=='Donatur'){
            $donatur=Donatur::where('donatur_status','1')->get();
            return view('pesan/pesannewdonatur');
        }
        if($entitas=='Santri'){
            $santri=Santri::where('santri_status','1')->get();
            return view('pesan/pesannewsantri');
        }
        if($entitas=='Pendamping'){
            $pendamping=Pendamping::where('pendamping_status')->get(); 
            return view('pesan/pesannewpendamping');
        }
    }
    public function newpesan(Request $request){
        $selectedentitas=$request->get('id_entitas');
        $jenisentitas=$request->get('jenis_entitas');
        // $arrentitas=json_encode($this->processSendHadist($jenisentitas,$hadistid,$selectedentitas));
        return view('pesan/pesannew',compact('selectedentitas','jenisentitas'));
    }

    
}
