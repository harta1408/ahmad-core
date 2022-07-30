<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Hadist;
use App\Models\Donatur;
use App\Models\Santri;
use App\Models\Pendamping;
use Validator;

class HadistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('hadist/hadistindex');
    }
    public function main(Request $request){
        $hadistid=$request->get('hadist_id');
        $hadiststatus=$request->get("hadist_state");

        if($hadiststatus=="NEW"){
            return view("hadist/hadistnew");
        }
        
        if($hadiststatus=="UPDATE"){
            $hadist=hadist::where('id',$hadistid)->first();
            return view("hadist/hadistupdate",compact('hadist'));
        }
        if($hadiststatus=="SEND"){
            return view("hadist/hadistsendmenu",compact('hadistid'));
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $hadist=Hadist::where('hadist_status','1')->get();
        $hadist=Hadist::orderBy('id','desc')->get();

        foreach ($hadist as $key => $hdst) {
            //deskripsi jenis hadist
            if($hdst->hadist_jenis=='1'){
                $hdst->hadist_jenis="HADIST";
            }
            if($hdst->hadist_jenis=='2'){
                $hdst->hadist_jenis="DOA";
            }
            if($hdst->hadist_status=='0'){
                $hdst->hadist_status="Tidak";
            }else{
                $hdst->hadist_status="Aktif";
            }
        }
        return $hadist;
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
            'hadist_judul' => 'required|string',
            'hadist_jenis' => 'required|string', 
            'hadist_isi' => 'required|string', 
            'hadist_isi_singkat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        try {
            $hadist=new Hadist;
            $hadist->hadist_jenis=$request->get('hadist_jenis'); 
            $hadist->hadist_judul=$request->get('hadist_judul'); 
            $hadist->hadist_isi=$request->get('hadist_isi');
            $hadist->hadist_lokasi_video=$request->get('hadist_lokasi_video' );  
            $hadist->hadist_isi_singkat=substr($request->get('hadist_isi_singkat'),0,255); 
            $hadist->hadist_status='1'; //aktif 
            $exec = $hadist->save();
            if (!$exec) {
                return response()->json(['status' => 'error', 'message' => 'System error', 'code' => 404]);
            }
            return redirect()->action('HadistController@index');
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
        $validator = Validator::make($request->all(), [
            'hadist_isi_singkat' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        $res = Hadist::where('id', $id)->update($request->except(['id','_token','_method']));

        if (!$res) {
            return response()->json(['status' => 'error', 'message' => 'System Error', 'code' => 404]);
        }
        return redirect()->action('HadistController@index');
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

    public function mainsend(Request $request){
        $entitas=$request->get('pilihan');
        $hadistid=$request->get('hadistid');
        if($entitas=='Semua'){
            $arrentitas=json_encode($this->processSendHadist('SEMUA',$hadistid,$entitas));
            // dd($arrentitas);
            return view('hadist/hadistsendlist',compact('arrentitas'));
        }
        if($entitas=='Donatur'){
            $donatur=Donatur::where('donatur_status','1')->get();
            return view('hadist/hadistdonatur',compact('hadistid'));
        }
        if($entitas=='Santri'){
            $santri=Santri::where('santri_status','1')->get();
            return view('hadist/hadistsantri',compact('hadistid'));
        }
        if($entitas=='Pendamping'){
            $pendamping=Pendamping::where('pendamping_status')->get(); 
            return view('hadist/hadistpendamping',compact('hadistid'));
        }
    }
    public function send(Request $request){
        $selectedentitas=explode(",",$request->get('id_entitas'));
        $jenisentitas=$request->get('jenis_entitas');
        $hadistid=$request->get('hadistid');
        $arrentitas=json_encode($this->processSendHadist($jenisentitas,$hadistid,$selectedentitas));
        return view('hadist/hadistsendlist',compact('arrentitas'));
    }


    private function processSendHadist($jenisentitas,$hadistid,$selectedentitas){
        #proses pengiriman hadist dan doa untuk masing masing entitas
        #setelah penyimpanan kedalam tabel hadis entitas, dilanjutkan dengan memasukan 
        #kedalam array, proses ini dilakukan untuk menampilkan data yang tersimpan dalam
        #daftar, karena setiap entitas beda tabel, maka ada proses penggabungan dalam 
        #array entitas
        $arrentitas=array();
        $hadist=Hadist::where('id',$hadistid)->first();
        #pilihan berdasarkan semua entitas 
        if($jenisentitas=='SEMUA'){
            $donatur=Donatur::where('donatur_status','!=','0')->get();
            foreach ($donatur as $key => $don) {
                $donaturid=$don->id;
                $donaturUpdate=Donatur::where('id',$donaturid)->first();
                #hapus memastikan data tidak ganda
                $donaturUpdate->hadist()->detach($hadistid);
                $donaturUpdate->hadist()->attach(['donatur_id'=>$donaturid],[
                    'hadist_id' =>$hadistid,
                    'hadist_donatur_status' =>'0',
                ]);
                $arrentitas[]= array('entitas_id' => $donaturid, 'entitas_kode' =>$don->donatur_kode,
                    'entitas_jenis'=>'Donatur', 'entitas_nama'=>$don->donatur_nama);
            }

            $santri=Santri::where('santri_status','!=','0')->get();
            foreach ($santri as $key => $san) {
                $santriid=$san->id;
                $santriUpdate=Santri::where('id',$santriid)->first();
                #hapus memastikan data tidak ganda
                $santriUpdate->hadist()->detach($hadistid);
                $santriUpdate->hadist()->attach(['santri_id'=>$santriid],[
                    'hadist_id' =>$hadistid,
                    'hadist_santri_status' =>'0',
                ]);
                $arrentitas[]= array('entitas_id' => $santriid, 'entitas_kode' =>$san->santri_kode,
                    'entitas_jenis'=>'Santri', 'entitas_nama'=>$san->santri_nama);
            }

            $pendamping=Pendamping::where('pendamping_status','!=','0')->get();
            foreach ($pendamping as $key => $pend) {
                $pendampingid=$pend->id;
                $pendampingUpdate=Pendamping::where('id',$pendampingid)->first();
                #hapus memastikan data tidak ganda
                $pendampingUpdate->hadist()->detach($hadistid);
                $pendampingUpdate->hadist()->attach(['pendamping_id'=>$pendampingid],[
                    'hadist_id' =>$hadistid,
                    'hadist_pendamping_status' =>'0',
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
                $donaturUpdate->hadist()->detach($hadistid);
                $donaturUpdate->hadist()->attach(['donatur_id'=>$donaturid],[
                    'hadist_id' =>$hadistid,
                    'hadist_donatur_status' =>'0',
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
                $santriUpdate->hadist()->detach($hadistid);
                $santriUpdate->hadist()->attach(['santri_id'=>$santriid],[
                    'hadist_id' =>$hadistid,
                    'hadist_santri_status' =>'0',
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
                $pendampingUpdate->hadist()->detach($hadistid);
                $pendampingUpdate->hadist()->attach(['pendamping_id'=>$pendampingid],[
                    'hadist_id' =>$hadistid,
                    'hadist_pendamping_status' =>'0',
                ]);
                $arrentitas[]= array('entitas_id' => $pend->id,  'entitas_kode' => $pend->pendamping_kode,
                'entitas_jenis'=>'Pendamping', 'entitas_nama'=>$pend->pendamping_nama);
            }
        }

        // dd($arrentitas);

        return $arrentitas;
    }
    public function hadistVideoIndex(){
        return view('hadist/hadistvideolist');
    }
}
