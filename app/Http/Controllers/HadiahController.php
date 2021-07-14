<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Hadiah;

class HadiahController extends Controller
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
        return view('hadiah/hadiahindex');
    }
    public function main(Request $request){
        $hadiahid=$request->get('hadiah_id');
        $hadiahstatus=$request->get("hadiah_state");

        if($hadiahstatus=="NEW"){
            return view("hadiah/hadiahnew");
        }

        $hadiah=Hadiah::where('id',$hadiahid)->first();
        if($hadiahstatus=="UPDATE"){
            return view("hadiah/hadiahupdate",compact('berita'));
        }
        $entitas=$hadiah->hadiah_entitas;
        if($hadiahstatus=="SEND"){
            if($entitas=='0'){
                $arrentitas=json_encode($this->processSendBerita('SEMUA',$hadiahid,$entitas));
                // dd($arrentitas);
                return view('hadiah/hadiahsendlist',compact('arrentitas'));
            }
            if($entitas=='1'){
                $donatur=Donatur::where('donatur_status','1')->get();
                return view('hadiah/hadiahdonatur',compact('beritaid'));
            }
            if($entitas=='2'){
                $santri=Santri::where('santri_status','1')->get();
                return view('hadiah/hadiahsantri',compact('beritaid'));
            }
            if($entitas=='3'){
                $pendamping=Pendamping::where('pendamping_status')->get(); 
                return view('hadiah/hadiahpendamping',compact('beritaid'));
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
        $hadiah=Hadiah::where('hadiah_status','1')->get();
        foreach ($hadiah as $key => $hdh) {
            //deskripsi jenis berita
            if($hdh->hadiah_jenis=='1'){
                $hdh->hadiah_jenis="Nominal";
            }
            if($hdh->hadiah_jenis=='2'){
                $hdh->hadiah_jenis="Produk";
            }
        }
        return $hadiah;
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
            'hadiah_jenis' => 'required|string',
            'hadiah_judul' => 'required|string', 
            'hadiah_entitas' => 'required|string', 
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        try {
            $hadiah=new Berita;
            $hadiah->hadiah_jenis=$request->get('hadiah_jenis'); 
            $hadiah->hadiah_judul=$request->get('hadiah_judul'); 
            $hadiah->hadiah_isi=$request->get('hadiah_isi'); 
            $hadiah->hadiah_entitas=$request->get('hadiah_entitas'); 
            $hadiah->hadiah_status='1'; //aktif 
            $exec = $hadiah->save();
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
        $res = Hadiah::where('id', $id)->update($request->except(['id','_token','_method']));

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
        $hadiahid=$request->get('beritaid');
        $arrentitas=json_encode($this->processSendBerita($jenisentitas,$hadiahid,$selectedentitas));
        return view('hadiah/hadiahsendlist',compact('arrentitas'));
    }
    private function processSendBerita($jenisentitas,$hadiahid,$selectedentitas){
        #proses pengiriman berita dan doa untuk masing masing entitas
        #setelah penyimpanan kedalam tabel hadis entitas, dilanjutkan dengan memasukan 
        #kedalam array, proses ini dilakukan untuk menampilkan data yang tersimpan dalam
        #daftar, karena setiap entitas beda tabel, maka ada proses penggabungan dalam 
        #array entitas
        $arrentitas=array();
        $hadiah=Hadiah::where('id',$hadiahid)->first();
        #pilihan berdasarkan semua entitas 
        if($jenisentitas=='SEMUA'){
            $donatur=Donatur::where('donatur_status','!=','0')->get();
            foreach ($donatur as $key => $don) {
                $donaturid=$don->id;
                $donaturUpdate=Donatur::where('id',$donaturid)->first();
                #hapus memastikan data tidak ganda
                $donaturUpdate->berita()->detach($hadiahid);
                $donaturUpdate->berita()->attach(['donatur_id'=>$donaturid],[
                    'hadiah_id' =>$hadiahid,
                    'hadiah_donatur_status' =>'0',
                ]);
                $arrentitas[]= array('entitas_id' => $donaturid, 'entitas_kode' =>$don->donatur_kode,
                    'entitas_jenis'=>'Donatur', 'entitas_nama'=>$don->donatur_nama);
            }

            $santri=Santri::where('santri_status','!=','0')->get();
            foreach ($santri as $key => $san) {
                $santriid=$san->id;
                $santriUpdate=Santri::where('id',$santriid)->first();
                #hapus memastikan data tidak ganda
                $santriUpdate->berita()->detach($hadiahid);
                $santriUpdate->berita()->attach(['santri_id'=>$santriid],[
                    'hadiah_id' =>$hadiahid,
                    'hadiah_santri_status' =>'0',
                ]);
                $arrentitas[]= array('entitas_id' => $santriid, 'entitas_kode' =>$san->santri_kode,
                    'entitas_jenis'=>'Santri', 'entitas_nama'=>$san->santri_nama);
            }

            $pendamping=Pendamping::where('pendamping_status','!=','0')->get();
            foreach ($pendamping as $key => $pend) {
                $pendampingid=$pend->id;
                $pendampingUpdate=Pendamping::where('id',$pendampingid)->first();
                #hapus memastikan data tidak ganda
                $pendampingUpdate->berita()->detach($hadiahid);
                $pendampingUpdate->berita()->attach(['pendamping_id'=>$pendampingid],[
                    'hadiah_id' =>$hadiahid,
                    'hadiah_pendamping_status' =>'0',
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
                $donaturUpdate->berita()->detach($hadiahid);
                $donaturUpdate->berita()->attach(['donatur_id'=>$donaturid],[
                    'hadiah_id' =>$hadiahid,
                    'hadiah_donatur_status' =>'0',
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
                $santriUpdate->berita()->detach($hadiahid);
                $santriUpdate->berita()->attach(['santri_id'=>$santriid],[
                    'hadiah_id' =>$hadiahid,
                    'hadiah_santri_status' =>'0',
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
                $pendampingUpdate->berita()->detach($hadiahid);
                $pendampingUpdate->berita()->attach(['pendamping_id'=>$pendampingid],[
                    'hadiah_id' =>$hadiahid,
                    'hadiah_pendamping_status' =>'0',
                ]);
                $arrentitas[]= array('entitas_id' => $pend->id,  'entitas_kode' => $pend->pendamping_kode,
                'entitas_jenis'=>'Pendamping', 'entitas_nama'=>$pend->pendamping_nama);
            }
        }

        return $arrentitas;
    }
}
