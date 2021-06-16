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
        $pengingatid=$request->get('pengingat_id');
        $pengingatstatus=$request->get("pengingat_state");

        if($pengingatstatus=="NEW"){
            return view("pengingat/pengingatnew");
        }

        $pengingat=Pengingat::where('id',$pengingatid)->first();
        if($pengingatstatus=="UPDATE"){
            return view("pengingat/pengingatupdate",compact('pengingat'));
        }
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
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        try {
            $pengingat=new Pengingat;
            $pengingat->pengingat_jenis=$request->get('pengingat_jenis'); 
            $pengingat->pengingat_judul=$request->get('pengingat_judul'); 
            $pengingat->pengingat_isi=$request->get('pengingat_isi' ); 
            $pengingat->pengingat_status='1'; //aktif 
            $exec = $pengingat->save();

            if (!$exec) {
                return response()->json(['status' => 'error', 'message' => 'System error', 'code' => 404]);
            }
            return redirect()->action('PengingatController@index');
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
        $res = Pengingat::where('id', $id)->update($request->except(['id','_token','_method']));

        if (!$res) {
            return response()->json(['status' => 'error', 'message' => 'System Error', 'code' => 404]);
        }
        return redirect()->action('PengingatController@index');
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
