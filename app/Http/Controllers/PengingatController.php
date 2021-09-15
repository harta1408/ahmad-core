<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pengingat;
use Validator;

class PengingatController extends Controller
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
        return view('pengingat/pengingatindex');
    }
    public function main(Request $request){
        $entitas=$request->get('pilihan');
        if($entitas=='Donatur'){
            return view("pengingat/pengingatdonaturindex");
        }
        if($entitas=='Santri'){
            return view("pengingat/pengingatsantriindex");
        }

        $beritacontrol=new BeritaController;
        $berita=$beritacontrol->panggilBeritaBroadcast($entitas);

        return view('referral/referralkontenupdate',compact('berita'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pengingat=Pengingat::where('pengingat_status','1')->get();
        foreach ($pengingat as $key => $pngt) {
            if($pngt->pengingat_jenis=='1'){
                $pngt->pengingat_jenis="Sedekah Shubuh";
            }
            if($pngt->pengingat_jenis=='2'){
                $pngt->pengingat_jenis="Sedekah Jum'at";
            }
            if($pngt->pengingat_jenis=='3'){
                $pngt->pengingat_jenis="Sedekah Yaumul Bidh";
            }
            if($pngt->pengingat_jenis=='4'){
                $pngt->pengingat_jenis="Sunnah Senin";
            }
            if($pngt->pengingat_jenis=='5'){
                $pngt->pengingat_jenis="Sunnah Kamis";
            }
            if($pngt->pengingat_jenis=='6'){
                $pngt->pengingat_jenis="Sunnah Jum'at";
            }
            if($pngt->pengingat_jenis=='7'){
                $pngt->pengingat_jenis="Pertemuan Online";
            }
            if($pngt->pengingat_jenis=='8'){
                $pngt->pengingat_jenis="Pertemuan Offline";
            }
            if($pngt->pengingat_jenis=='9'){
                $pngt->pengingat_jenis="Talkin Dzikir";
            }
        }
        return $pengingat;
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
            'pengingat_jenis' => 'required|string',
            'pengingat_judul' => 'required|string', 
            'pengingat_isi_singkat' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        $entitas=$request->get('pengingat_entitas');
        try {
            $pengingatjenis=$request->get('pengingat_jenis'); 
            $index=1;
            if($entitas=='2'){
                if($pengingatjenis=='4' || $pengingatjenis=='5' || $pengingatjenis=='6' ){
                    $index=$this->createindex();
                }
            }
            $pengingat=new Pengingat;
            $pengingat->pengingat_jenis=$pengingatjenis;
            $pengingat->pengingat_judul=$request->get('pengingat_judul'); 
            $pengingat->pengingat_entitas=$entitas;
            $pengingat->pengingat_index=$index;
            $pengingat->pengingat_isi=$request->get('pengingat_isi' ); 
            $pengingat->pengingat_lokasi_video=$request->get('pengingat_lokasi_video' ); 
            $pengingat->pengingat_isi_singkat=substr($request->get('pengingat_isi_singkat'),0,255); 
            $pengingat->pengingat_status='1'; //aktif 
            $exec = $pengingat->save();

            if (!$exec) {
                return response()->json(['status' => 'error', 'message' => 'System error', 'code' => 404]);
            }
            if($entitas=='1'){
                return redirect()->action('PengingatController@pengingatDonaturIndex');
            }else{
                return redirect()->action('PengingatController@pengingatSantriIndex');
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'code' => 404]);
        }
        return response()->json(['status' => 'error', 'message' => 'System error', 'code' => 404]);
  
    }
    private function createindex(){
        for ($i=1; $i <100 ; $i++) { 
            $pengingat=Pengingat::where('pengingat_index',$i)->whereIn('pengingat_jenis',['4','5','6'])->first();
            if(!$pengingat){
                return $i;
            }
        }
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
        // dd($request);
        $validator = Validator::make($request->all(), [
            'pengingat_isi_singkat' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        $res = Pengingat::where('id', $id)->update($request->except(['id','_token','_method']));
        if (!$res) {
            return response()->json(['status' => 'error', 'message' => 'System Error', 'code' => 404]);
        }

        $entitas=$request->get('pengingat_entitas');
        if($entitas=='1'){
            return redirect()->action('PengingatController@pengingatDonaturIndex');
        }else{
            return redirect()->action('PengingatController@pengingatSantriIndex');
        }
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

    #modul untuk pengaturan pengingat donatur
    public function pengingatDonaturLoad(){
        $pengingat=Pengingat::where([['pengingat_entitas','1'],['pengingat_status','1']])
            ->orderBy('created_at','desc')->get();
        foreach ($pengingat as $key => $pngt) {
            if($pngt->pengingat_jenis=='1'){
                $pngt->pengingat_jenis="Sedekah Shubuh";
            }
            if($pngt->pengingat_jenis=='2'){
                $pngt->pengingat_jenis="Sedekah Jum'at";
            }
            if($pngt->pengingat_jenis=='3'){
                $pngt->pengingat_jenis="Sedekah Yaumul Bidh";
            }
        }
        return $pengingat;        
    }
    public function pengingatDonaturIndex(){
        return view('pengingat/pengingatdonaturindex');
    }
    public function pengingatDonaturMain(Request $request){
        $pengingatid=$request->get('pengingat_id');
        $pengingatstatus=$request->get("pengingat_state");

        if($pengingatstatus=="NEW"){
            return view("pengingat/pengingatdonaturnew");
        }

        $pengingat=Pengingat::where('id',$pengingatid)->first();
        if($pengingatstatus=="UPDATE"){
            return view("pengingat/pengingatdonaturupdate",compact('pengingat'));
        }
    }
    #modul untuk pengaturan pengingan santri
    public function pengingatSantriLoad(){
        $pengingat=Pengingat::where([['pengingat_entitas','2'],['pengingat_status','1']])
            ->whereIn('pengingat_jenis',['4','5','6'])->orderBy('created_at','desc')->get();
        foreach ($pengingat as $key => $pngt) {
            if($pngt->pengingat_jenis=='4'){
                $pngt->pengingat_jenis="Sunnah Senin";
            }
            if($pngt->pengingat_jenis=='5'){
                $pngt->pengingat_jenis="Sunnah Kamis";
            }
            if($pngt->pengingat_jenis=='6'){
                $pngt->pengingat_jenis="Sunnah Jum'at";
            }
            if($pngt->pengingat_jenis=='7'){
                $pngt->pengingat_jenis="Pertemuan Online";
            }
            if($pngt->pengingat_jenis=='8'){
                $pngt->pengingat_jenis="Pertemuan Offline";
            }
            if($pngt->pengingat_jenis=='9'){
                $pngt->pengingat_jenis="Talkin Dzikir";
            }
        }
        return $pengingat;        
    }
    public function pengingatSantriIndex(){
        return view('pengingat/pengingatsantriindex');
    }
    public function pengingatSantriMain(Request $request){
        $pengingatid=$request->get('pengingat_id');
        $pengingatstatus=$request->get("pengingat_state");

        if($pengingatstatus=="NEW"){
            return view("pengingat/pengingatsantrinew");
        }

        $pengingat=Pengingat::where('id',$pengingatid)->first();
        if($pengingatstatus=="UPDATE"){
            return view("pengingat/pengingatsantriupdate",compact('pengingat'));
        }
    }
    #modul untuk daftar pengingat dari pendamping untuk santri
    public function pengingatPendampingIndex(){
        return view('pengingat/pengingatpendampingindex');
    }
    public function pengingatPendampingMain(Request $request){
        $pendampingid=$request->get('id_entitas');
        $pendamping=function ($query) use ($pendampingid){
            $query->where('id',$pendampingid);
        };
        $pengingat=Pengingat::with(['pendamping'=>$pendamping,'santri'])->whereHas('pendamping',$pendamping)
            ->whereIn('pengingat_jenis',['7','8','9'])->get();
            foreach ($pengingat as $key => $pngt) {
                if($pngt->pengingat_jenis=='1'){
                    $pngt->pengingat_jenis="Sedekah Shubuh";
                }
                if($pngt->pengingat_jenis=='2'){
                    $pngt->pengingat_jenis="Sedekah Jum'at";
                }
                if($pngt->pengingat_jenis=='3'){
                    $pngt->pengingat_jenis="Sedekah Yaumul Bidh";
                }
                if($pngt->pengingat_jenis=='4'){
                    $pngt->pengingat_jenis="Sunnah Senin";
                }
                if($pngt->pengingat_jenis=='5'){
                    $pngt->pengingat_jenis="Sunnah Kamis";
                }
                if($pngt->pengingat_jenis=='6'){
                    $pngt->pengingat_jenis="Sunnah Jum'at";
                }
                if($pngt->pengingat_jenis=='7'){
                    $pngt->pengingat_jenis="Pertemuan Online";
                }
                if($pngt->pengingat_jenis=='8'){
                    $pngt->pengingat_jenis="Pertemuan Offline";
                }
                if($pngt->pengingat_jenis=='9'){
                    $pngt->pengingat_jenis="Talkin Dzikir";
                }
            }
        return view("pengingat/pengingatpendampingmain",compact('pengingat'));

    }
    #modul untuk daftar video
    public function pengingatVideoIndex(){
        return view('pengingat/pengingatvideolist');
    }
    public function pengingatVideoLoad(){
        $pengingat=Pengingat::where([['pengingat_status','1'],['pengingat_lokasi_video','!=','']])
            ->orderBy('created_at','desc')->get();
        foreach ($pengingat as $key => $pngt) {
            if($pngt->pengingat_jenis=='1'){
                $pngt->pengingat_jenis="Sedekah Shubuh";
            }
            if($pngt->pengingat_jenis=='2'){
                $pngt->pengingat_jenis="Sedekah Jum'at";
            }
            if($pngt->pengingat_jenis=='3'){
                $pngt->pengingat_jenis="Sedekah Yaumul Bidh";
            }
            if($pngt->pengingat_jenis=='4'){
                $pngt->pengingat_jenis="Sunnah Senin";
            }
            if($pngt->pengingat_jenis=='5'){
                $pngt->pengingat_jenis="Sunnah Kamis";
            }
            if($pngt->pengingat_jenis=='6'){
                $pngt->pengingat_jenis="Sunnah Jum'at";
            }
            if($pngt->pengingat_jenis=='7'){
                $pngt->pengingat_jenis="Pertemuan Online";
            }
            if($pngt->pengingat_jenis=='8'){
                $pngt->pengingat_jenis="Pertemuan Offline";
            }
            if($pngt->pengingat_jenis=='9'){
                $pngt->pengingat_jenis="Talkin Dzikir";
            }
        }
        return $pengingat;     
    }
}
