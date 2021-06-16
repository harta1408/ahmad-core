<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pesan;
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
            return view("pesan/pesannew");
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
        $pesan=Pesan::where('pesan_status','1')->get();
        foreach ($pesan as $key => $psn) {
            if($psn->pesan_entitas=='0'){
                $psn->pesan_entitas="Semua";
            }
            if($psn->pesan_entitas=='1'){
                $psn->pesan_entitas="Donatur";
            }
            if($psn->pesan_entitas=='2'){
                $psn->pesan_entitas="Santri";
            }
            if($psn->pesan_entitas=='3'){
                $psn->pesan_entitas="Pendamping";
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
            'pesan_entitas' => 'required|string',  
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        try {
            $pesan=new Pesan;
            $pesan->pembuat_id=$request->get('pembuat_id'); 
            $pesan->pesan_entitas=$request->get('pesan_entitas'); 
            $pesan->pesan_judul=$request->get('pesan_judul'); 
            $pesan->pesan_isi=$request->get('pesan_isi'); 
            $pesan->pesan_waktu_kirim=$request->get('pesan_waktu_kirim'); 
            $pesan->pesan_status='1'; //aktif 
            $exec = $pesan->save();
            if (!$exec) {
                return response()->json(['status' => 'error', 'message' => 'System error', 'code' => 404]);
            }
            return redirect()->action('PesanController@index');
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
}
