<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Donatur;
use App\Models\Santri;
use App\Models\Pendamping;
use Validator;

class BeritaController extends Controller
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
        return view('berita/beritaindex');
    }
    public function main(Request $request){
        $beritaid=$request->get('berita_id');
        $beritastatus=$request->get("berita_state");

        if($beritastatus=="NEW"){
            return view("berita/beritanew");
        }

        $berita=Berita::where('id',$beritaid)->first();
        if($beritastatus=="UPDATE"){
            return view("berita/beritaupdate",compact('berita'));
        }
        $entitas=$berita->berita_entitas;
        if($beritastatus=="SEND"){
            if($entitas=='0'){
                $arrentitas=json_encode($this->processSendBerita('SEMUA',$beritaid,$entitas));
                // dd($arrentitas);
                return view('berita/beritasendlist',compact('arrentitas'));
            }
            if($entitas=='1'){
                $donatur=Donatur::where('donatur_status','1')->get();
                return view('berita/beritadonatur',compact('beritaid'));
            }
            if($entitas=='2'){
                $santri=Santri::where('santri_status','1')->get();
                return view('berita/beritasantri',compact('beritaid'));
            }
            if($entitas=='3'){
                $pendamping=Pendamping::where('pendamping_status')->get(); 
                return view('berita/beritapendamping',compact('beritaid'));
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        #hanya menampilkan berita yang dikirim oleh lembaga untuk entitas
        $berita=Berita::where([['berita_status','1'],['berita_jenis','1']])->get();
        foreach ($berita as $key => $pngt) {
            //deskripsi jenis berita
            if($pngt->berita_jenis=='1'){
                $pngt->berita_jenis="Berita";
            }
            if($pngt->berita_jenis=='2'){
                $pngt->berita_jenis="Kampanye";
            }
            if($pngt->berita_jenis=='3'){
                $pngt->berita_jenis="Broadcast";
            }

            //deskripsi entitas
            if($pngt->berita_entitas=='0'){
                $pngt->berita_entitas="Semua";
            }
            if($pngt->berita_entitas=='1'){
                $pngt->berita_entitas="Donatur";
            }
            if($pngt->berita_entitas=='2'){
                $pngt->berita_entitas="Santri";
            }
            if($pngt->berita_entitas=='3'){
                $pngt->berita_entitas="Pendamping";
            }
        }
        return $berita;
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
            'berita_jenis' => 'required|string',
            'berita_judul' => 'required|string', 
            'berita_entitas' => 'required|string', 
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        try {
            $berita=new Berita;
            $berita->berita_jenis=$request->get('berita_jenis'); 
            $berita->berita_judul=$request->get('berita_judul'); 
            $berita->berita_isi=$request->get('berita_isi'); 
            $berita->berita_entitas=$request->get('berita_entitas'); 
            $berita->berita_index='00'; 
            $berita->berita_status='1'; //aktif 
            $exec = $berita->save();
            if (!$exec) {
                return response()->json(['status' => 'error', 'message' => 'System error', 'code' => 404]);
            }
            return redirect()->action('BeritaController@index');
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'code' => 404]);
        }
        return response()->json(['status' => 'error', 'message' => 'System error', 'code' => 404]);
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
        $res = Berita::where('id', $id)->update($request->except(['id','_token','_method']));

        if (!$res) {
            return response()->json(['status' => 'error', 'message' => 'System Error', 'code' => 404]);
        }
        return redirect()->action('BeritaController@index');
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
   
    public function send(Request $request){
        $selectedentitas=explode(",",$request->get('id_entitas'));
        $jenisentitas=$request->get('jenis_entitas');
        $beritaid=$request->get('beritaid');
        $arrentitas=json_encode($this->processSendBerita($jenisentitas,$beritaid,$selectedentitas));
        return view('berita/beritasendlist',compact('arrentitas'));
    }
    private function processSendBerita($jenisentitas,$beritaid,$selectedentitas){
        #proses pengiriman berita dan doa untuk masing masing entitas
        #setelah penyimpanan kedalam tabel hadis entitas, dilanjutkan dengan memasukan 
        #kedalam array, proses ini dilakukan untuk menampilkan data yang tersimpan dalam
        #daftar, karena setiap entitas beda tabel, maka ada proses penggabungan dalam 
        #array entitas
        $arrentitas=array();
        $berita=Berita::where('id',$beritaid)->first();
        #pilihan berdasarkan semua entitas 
        if($jenisentitas=='SEMUA'){
            $donatur=Donatur::where('donatur_status','!=','0')->get();
            foreach ($donatur as $key => $don) {
                $donaturid=$don->id;
                $donaturUpdate=Donatur::where('id',$donaturid)->first();
                #hapus memastikan data tidak ganda
                $donaturUpdate->berita()->detach($beritaid);
                $donaturUpdate->berita()->attach(['donatur_id'=>$donaturid],[
                    'berita_id' =>$beritaid,
                    'berita_donatur_status' =>'0',
                ]);
                $arrentitas[]= array('entitas_id' => $donaturid, 'entitas_kode' =>$don->donatur_kode,
                    'entitas_jenis'=>'Donatur', 'entitas_nama'=>$don->donatur_nama);
            }

            $santri=Santri::where('santri_status','!=','0')->get();
            foreach ($santri as $key => $san) {
                $santriid=$san->id;
                $santriUpdate=Santri::where('id',$santriid)->first();
                #hapus memastikan data tidak ganda
                $santriUpdate->berita()->detach($beritaid);
                $santriUpdate->berita()->attach(['santri_id'=>$santriid],[
                    'berita_id' =>$beritaid,
                    'berita_santri_status' =>'0',
                ]);
                $arrentitas[]= array('entitas_id' => $santriid, 'entitas_kode' =>$san->santri_kode,
                    'entitas_jenis'=>'Santri', 'entitas_nama'=>$san->santri_nama);
            }

            $pendamping=Pendamping::where('pendamping_status','!=','0')->get();
            foreach ($pendamping as $key => $pend) {
                $pendampingid=$pend->id;
                $pendampingUpdate=Pendamping::where('id',$pendampingid)->first();
                #hapus memastikan data tidak ganda
                $pendampingUpdate->berita()->detach($beritaid);
                $pendampingUpdate->berita()->attach(['pendamping_id'=>$pendampingid],[
                    'berita_id' =>$beritaid,
                    'berita_pendamping_status' =>'0',
                ]);
                $arrentitas[]= array('entitas_id' => $pend->id,  'entitas_kode' => $pend->pendamping_kode,
                'entitas_jenis'=>'Pendamping', 'entitas_nama'=>$pend->pendamping_nama);
            }
        }

        #pilihan berdasarkan entitas donatur
        if($jenisentitas=='DONATUR'){
            $donatur=Donatur::whereIn('id',$selectedentitas)->get();
            foreach ($donatur as $key => $don) {
                $donaturid=$don->id;
                $donaturUpdate=Donatur::where('id',$donaturid)->first();
                #hapus memastikan data tidak ganda
                $donaturUpdate->berita()->detach($beritaid);
                $donaturUpdate->berita()->attach(['donatur_id'=>$donaturid],[
                    'berita_id' =>$beritaid,
                    'berita_donatur_status' =>'0',
                ]);
                $arrentitas[]= array('entitas_id' => $donaturid, 'entitas_kode' =>$don->donatur_kode,
                    'entitas_jenis'=>'Donatur', 'entitas_nama'=>$don->donatur_nama);
            }
        }
         #pilihan berdasarkan entitas santri
        if($jenisentitas=='SANTRI'){
            $santri=Santri::whereIn('id',$selectedentitas)->get();
            foreach ($santri as $key => $san) {
                $santriid=$san->id;
                $santriUpdate=Santri::where('id',$santriid)->first();
                #hapus memastikan data tidak ganda
                $santriUpdate->berita()->detach($beritaid);
                $santriUpdate->berita()->attach(['santri_id'=>$santriid],[
                    'berita_id' =>$beritaid,
                    'berita_santri_status' =>'0',
                ]);
                $arrentitas[]= array('entitas_id' => $santriid, 'entitas_kode' =>$san->santri_kode,
                    'entitas_jenis'=>'Santri', 'entitas_nama'=>$san->santri_nama);
            }
        }
         #pilihan berdasarkan entitas pendamping
        if($jenisentitas=='PENDAMPING'){
            $pendamping=Pendamping::whereIn('id',$selectedentitas)->get();
            foreach ($pendamping as $key => $pend) {
                $pendampingid=$pend->id;
                $pendampingUpdate=Pendamping::where('id',$pendampingid)->first();
                #hapus memastikan data tidak ganda
                $pendampingUpdate->berita()->detach($beritaid);
                $pendampingUpdate->berita()->attach(['pendamping_id'=>$pendampingid],[
                    'berita_id' =>$beritaid,
                    'berita_pendamping_status' =>'0',
                ]);
                $arrentitas[]= array('entitas_id' => $pend->id,  'entitas_kode' => $pend->pendamping_kode,
                'entitas_jenis'=>'Pendamping', 'entitas_nama'=>$pend->pendamping_nama);
            }
        }

        return $arrentitas;
    }

    public function panggilBeritaBroadcast($entitas){
        $beritaentitas='1';
        if($entitas=='Donatur'){
            $beritaentitas='1';
        }
        if($entitas=='Santri'){
            $beritaentitas='2';
        }
        if($entitas=='Pendamping'){
            $beritaentitas='3';
        }
        $sudahada=Berita::where([['berita_entitas',$beritaentitas],['berita_jenis','3']])->count();
        if($sudahada==0){
            #buat baru
            $berita=new Berita;
            $berita->berita_jenis='3'; 
            $berita->berita_judul='Broadcast '.$entitas; 
            $berita->berita_index='00'; 
            $berita->berita_isi='Bergabung dengan AHMaD Project sebagai '.$entitas; 
            $berita->berita_entitas=$beritaentitas; 
            $berita->berita_status='1'; //aktif 
            $exec = $berita->save();
        }

        $berita=Berita::where([['berita_entitas',$beritaentitas],['berita_jenis','3']])->first();
        return $berita;
    }

    #modul untuk menangani kampanye 
    public function beritaKampanyeIndex(){
        return view('berita/kampanyeindex');
    }
    public function beritaKampanyeLoad(){
        #hanya menampilkan berita yang dikirim oleh lembaga untuk entitas
        $berita=Berita::where([['berita_status','1'],['berita_jenis','2']])->get();
        foreach ($berita as $key => $pngt) {
            //deskripsi jenis berita
            if($pngt->berita_jenis=='1'){
                $pngt->berita_jenis="Berita";
            }
            if($pngt->berita_jenis=='2'){
                $pngt->berita_jenis="Kampanye";
            }
            if($pngt->berita_jenis=='3'){
                $pngt->berita_jenis="Broadcast";
            }

            //deskripsi entitas
            if($pngt->berita_entitas=='0'){
                $pngt->berita_entitas="Semua";
            }
            if($pngt->berita_entitas=='1'){
                $pngt->berita_entitas="Donatur";
            }
            if($pngt->berita_entitas=='2'){
                $pngt->berita_entitas="Santri";
            }
            if($pngt->berita_entitas=='3'){
                $pngt->berita_entitas="Pendamping";
            }
        }
        return $berita;        
    }
    public function beritaKampanyeMain(Request $request){
        $beritaid=$request->get('berita_id');
        $beritastatus=$request->get("berita_state");

        if($beritastatus=="NEW"){
            return view("berita/kampanyenew");
        }

        $berita=Berita::where('id',$beritaid)->first();
        if($beritastatus=="UPDATE"){
            return view("berita/kampanyeupdate",compact('berita'));
        }    
        if($beritastatus=="PREVIEW"){
            return view("berita/kampanyevideo",compact('berita'));
        }    
    }
    public function beritaKampanyeSave(Request $request){
        $validator = Validator::make($request->all(), [
            'berita_jenis' => 'required|string',
            'berita_judul' => 'required|string', 
            'berita_entitas' => 'required|string', 
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        try {
            $berita=new Berita;
            $berita->berita_jenis=$request->get('berita_jenis'); 
            $berita->berita_index=$request->get('berita_index'); 
            $berita->berita_judul=$request->get('berita_judul'); 
            $berita->berita_isi=$request->get('berita_isi'); 
            $berita->berita_entitas=$request->get('berita_entitas'); 
            $berita->berita_lokasi_video=$request->get('berita_lokasi_video'); 
            $berita->berita_status='1'; //aktif 
            $exec = $berita->save();
            if (!$exec) {
                return response()->json(['status' => 'error', 'message' => 'System error', 'code' => 404]);
            }
            return redirect()->action('BeritaController@beritaKampanyeIndex');
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'code' => 404]);
        }
        return response()->json(['status' => 'error', 'message' => 'System error', 'code' => 404]);
    }
    public function beritaKampanyeUpdate(Request $request, $id)
    {
        $res = Berita::where('id', $id)->update($request->except(['id','_token','_method']));

        if (!$res) {
            return response()->json(['status' => 'error', 'message' => 'System Error', 'code' => 404]);
        }
        return redirect()->action('BeritaController@beritaKampanyeIndex');
    }

    public function beritaVideoIndex(){
        return view('berita/beritavideolist');
    }
    public function beritaVideoLoad(){
          #hanya menampilkan video untuk berita jenis 1 dan 2
          $berita=Berita::where('berita_status','1')->whereIn('berita_jenis',['1','2'])->get();
          foreach ($berita as $key => $pngt) {
              //deskripsi jenis berita
              if($pngt->berita_jenis=='1'){
                  $pngt->berita_jenis="Berita";
              }
              if($pngt->berita_jenis=='2'){
                  $pngt->berita_jenis="Kampanye";
              }
              if($pngt->berita_jenis=='3'){
                  $pngt->berita_jenis="Broadcast";
              }
  
              //deskripsi entitas
              if($pngt->berita_entitas=='0'){
                  $pngt->berita_entitas="Semua";
              }
              if($pngt->berita_entitas=='1'){
                  $pngt->berita_entitas="Donatur";
              }
              if($pngt->berita_entitas=='2'){
                  $pngt->berita_entitas="Santri";
              }
              if($pngt->berita_entitas=='3'){
                  $pngt->berita_entitas="Pendamping";
              }
          }
          return $berita;   
    }
}
