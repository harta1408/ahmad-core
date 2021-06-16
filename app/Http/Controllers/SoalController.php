<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Soal;
use App\Models\Materi;
use Validator;

class SoalController extends Controller
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
        return view('soal/soalindex');

    }
    public function main(Request $request){
        $soalstatus=$request->get('soal_state');
        $soalid=$request->get('soal_id');

        $soal=Soal::where('id',$soalid)->first();

        $materi=Materi::where('materi_status','1')->get();
        $pilihan=$soal->soal_jenis;

        if($soalstatus=='NEW'){ //baru
            return view('soal/soalnewmenu');
        }
        if($soalstatus=='UPDATE'){ //update
            if($pilihan=='1'){
                return view('soal/soalessayupdate',compact('soal','materi','pilihan'));
            }else{
                return view('soal/soalpilihanupdate',compact('soal','materi','pilihan'));
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
        $soal=Soal::with('materi')->where('soal_status','1')->get();
        foreach ($soal as $key => $sl) {
            if($sl->soal_jenis=='1'){
                $sl->soal_jenis='Essay';
            }else{
                $sl->soal_jenis='Pilihan';
            }
        }
        return $soal;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);

        // if (!$request->ajax()) {
        //     return response()->json(['status' => 'error', 'message' => 'Request is not permitted', 'code' => 404]);
        // }

        $validator = Validator::make($request->all(), [
            'soal_deskripsi' => 'required|string',
            'soal_jenis' => 'required|string', 
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $jenissoal=$request->get('soal_jenis');
        try {
            $soal=new Soal;
            $soal->materi_id=$request->get('materi_id'); 
            $soal->soal_no=$request->get('soal_no'); 
            $soal->soal_bab=$request->get('soal_bab'); 
            $soal->soal_deskripsi=$request->get('soal_deskripsi'); 
            $soal->soal_jenis=$request->get('soal_jenis'); 
            $soal->soal_nilai_maksimum=$request->get('soal_nilai_maksimum'); 
            $soal->soal_nilai_minimum=$request->get('soal_nilai_minimum'); 
            $soal->soal_status='1'; //aktif 
            if($jenissoal=='2'){
                $soal->soal_pilihan_a=$request->get('soal_pilihan_a' ); 
                $soal->soal_pilihan_b=$request->get('soal_pilihan_b'); 
                $soal->soal_pilihan_c=$request->get('soal_pilihan_c' ); 
                $soal->soal_pilihan_d=$request->get('soal_pilihan_d'); 
            }
            $exec = $soal->save();

            if (!$exec) {
                return response()->json(['status' => 'error', 'message' => 'System error', 'code' => 404]);
            }
            return redirect()->action('SoalController@index');

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
        $res = Soal::where('id', $id)->update($request->except(['id','_token','_method']));

        if (!$res) {
            return response()->json(['status' => 'error', 'message' => 'System Error', 'code' => 404]);
        }
        return redirect()->action('SoalController@index');
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

    public function soalNewMenu(Request $request){
        $materi=Materi::where('materi_status','1')->get();
        $pilihan=$request->get('pilihan');
        if($pilihan=="Essay"){
            $pilihan='1';
            return view ('soal/soalessay',compact('materi','pilihan'));
        }else{
            $pilihan='2';
            return view ('soal/soalpilihan',compact('materi','pilihan'));
        }
    }
}
