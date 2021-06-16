<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Donatur;
use App\Models\KodePos;
use App\Models\User;
use App\Http\Controllers\KodePosAPI;
use App\Http\Controllers\DonaturAPI;
use Validator;



class DonaturController extends Controller
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
        $donatur=Donatur::with('user')->where('donatur_status','!=','0')->get();
        foreach ($donatur as $key => $dnt) {
            if($dnt->user['email_verified_at']==null){
                $dnt->donatur_status="Belum Konfirmasi Email";
            }else{
                if($dnt->donatur_status=="2"){
                    $dnt->donatur_status="Belum Lengkap";
                }else{
                    $dnt->donatur_status="Sudah Lengkap";
                }
            }
        }
        return view('donatur/donaturlist',compact('donatur'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $provinsi=KodePos::groupBy('provinsi')->get('provinsi');
        // $kota=KodePos::groupBy('provinsi','kota')->get(['provinsi','kota']);
        // $kecamatan=KodePos::groupBy('provinsi','kota','kecamatan')->get(['kota','kecamatan']);
        // $kelurahan=KodePos::groupBy('provinsi','kota','kecamatan','kelurahan')->get(['kecamatan','kelurahan']);
        return view ('donatur/donaturnew');
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
            'donatur_email' => 'required|email|unique:donatur|max:100',
            'donatur_nama' => ['required','string','max:30'],
            'donatur_telepon' => 'required|string',
            'donatur_alamat'=>'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $donaturapi=new DonaturAPI;
        $donaturkode=$donaturapi->donaturKode();

        #buat hash code acak untuk default yang harus langsung diganti
        #ketika email terverifikasi
        #link verifikasi di panggil berdasarkan user, nama dan hash code
        $useremail=$request->get("donatur_email"); ; 
        $username=$request->get("donatur_nama");
        $url = public_path();
        $usertipe="1"; //tipe user donatur
        $hashcode=md5(rand(100000,999999)); 

        #buat user baru dengan alamat email yang dimasukan
        $user=new User;
        $user->email=$useremail;
        $user->name=$username;
        $user->hash_code=$hashcode; 
        $user->tipe=$usertipe;
        $user->password=Hash::make($donaturkode);
        $exec=$user->save();

        $donatur=new Donatur;
        $donatur->donatur_kode=$donaturkode;
        $donatur->donatur_email=$request->get("donatur_email"); 
        $donatur->donatur_nama=$request->get("donatur_nama");
        $donatur->donatur_nid=$request->get("donatur_nid");
        $donatur->donatur_gender=$request->get("donatur_gender");
        $donatur->donatur_agama=$request->get("donatur_agama");
        $donatur->donatur_telepon=$request->get("donatur_telepon");
        $donatur->donatur_kerja=$request->get("donatur_kerja");
        $donatur->donatur_tmp_lahir=$request->get("donatur_tmp_lahir");
        $donatur->donatur_tgl_lahir=$request->get("donatur_tgl_lahir");
        $donatur->donatur_alamat=$request->get("donatur_alamat");
        $donatur->donatur_provinsi=$request->get("donatur_provinsi");
        $donatur->donatur_kota=$request->get("donatur_kota");
        $donatur->donatur_kecamatan=$request->get("donatur_kecamatan");
        $donatur->donatur_kelurahan=$request->get("donatur_kelurahan");
        $donatur->donatur_kode_pos=$request->get("donatur_kode_pos");
        $donatur->donatur_status='1'; //aktif belum melengkapi data
        $donatur->save();

        // kirim email registrasi
        // $url=$url.'/register'.'/'.$hashcode;
        // $data = array('name'=>$username,'url'=>$url);
        // Mail::send('emailregister', $data, function($message) use($useremail, $username) {
        //    $message->to($useremail, $username)->subject
        //       ('no-reply : Pendaftaran AHMaD Project');
        //    $message->from('ahmad@gimanamas.com','AHMaD Project');
        // });

        return redirect()->action('DonaturController@index');
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
            'donatur_nama' => 'required|string',
            'donatur_telepon' => 'required|string',
            'donatur_alamat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $exec=Donatur::where('id','=' ,$id)
            ->update(['donatur_nid'=>$request->get('donatur_nid'),
                    'donatur_nama'=>$request->get('donatur_nama'),
                    'donatur_tmp_lahir'=>$request->get('donatur_tmp_lahir'), 
                    'donatur_tgl_lahir'=>$request->get('donatur_tgl_lahir'), 
                    'donatur_gender'=>$request->get('donatur_gender'), 
                    'donatur_agama'=>$request->get('donatur_agama'), 
                    'donatur_telepon'=>$request->get('donatur_telepon'), 
                    'donatur_kerja'=>$request->get('donatur_kerja'),
                    'donatur_alamat'=>$request->get('donatur_alamat'), 
                    'donatur_kode_pos'=>$request->get('donatur_kode_pos'),
                    'donatur_kelurahan'=>$request->get('donatur_kelurahan'),
                    'donatur_kecamatan'=>$request->get('donatur_kecamatan'),
                    'donatur_kota'=>$request->get('donatur_kota'),
                    'donatur_provinsi'=>$request->get('donatur_provinsi'),
                    'donatur_kode_pos' =>$request->get('donatur_kode_pos'),
                    'donatur_status' => '2', //data sudah lengkap
                ]);
        return redirect()->action('DonaturController@donaturRenewIndex');
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

    public function donaturRenewIndex(){
        $donatur=Donatur::with('user')->where('donatur_status','!=','0')->get();
        foreach ($donatur as $key => $dnt) {
            if($dnt->user['email_verified_at']==null){
                $dnt->donatur_status="Belum Konfirmasi Email";
            }else{
                if($dnt->donatur_status=="2"){
                    $dnt->donatur_status="Belum Lengkap";
                }else{
                    $dnt->donatur_status="Sudah Lengkap";
                }
            }
        }
        return view('donatur/donaturrenewindex',compact('donatur'));
    }
    public function donaturRenewMain(Request $request){
        $id=$request->get('donatur_id');
        $status=$request->get('donatur_state');

        if($status=="UPDATE"){
            $donatur=Donatur::where('id',$id)->first();  
            return view ('donatur/donaturupdate',compact('donatur'));
        }else{

        }


    }

}