<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Berita;
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
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $berita=Berita::where('berita_status','1')->get();
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
}
