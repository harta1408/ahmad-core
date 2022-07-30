<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Donatur;
use App\Models\KodePos;
use App\Models\User;
use App\Models\Donasi;
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
            // $dnt->donatur_status="Belum Lengkap";
            if($dnt->user==null){
                $dnt->donatur_status="Belum ada Email (User Login)";
                continue;
            }
            if($dnt->user['email_verified_at']==null){
                $dnt->donatur_status="Belum Konfirmasi Email";
            }else{
                if($dnt->donatur_status=="1"){
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
        $validator = Validator::make($request->form, [
            'donatur_email' => 'required|email|unique:donatur|max:100',
            'donatur_nama' => ['required','string','max:30'],
            'donatur_telepon' => 'required|string',
            'donatur_alamat'=>'required|string',
            'donatur_provinsi_id'=>'required|string',
            'donatur_kota_id'=>'required|string',
            'donatur_kecamatan_id'=>'required|string',
        ],[
            'donatur_email.required' => 'Silakan Masukan Alamat Email',
            'donatur_email.email' => 'Masukan dalam format Alamat Email', 
            'donatur_email.unique' => 'Alamat Email sudah terdaftar', 
            'donatur_nama.required' => 'Silakan isi Nama Agniya', 
            'donatur_telepon.required' => 'Silakan isi Telepon Agniya', 
            'donatur_alamat.required' => 'Silakan isi Alamat Agniya', 
            'donatur_provinsi_id.required' => 'Silakan Pilih Propinsi dari daftar', 
            'donatur_kota_id.required' => 'Silakan Pilih Kota dari Daftar', 
            'donatur_kecamatan_id.required' => 'Silakan Pilih Kecamatan dari Daftar', 
        ]);


        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $donaturapi=new DonaturAPI;
        $donaturkode=$donaturapi->donaturKode();

        #buat hash code acak untuk default yang harus langsung diganti
        #ketika email terverifikasi
        #link verifikasi di panggil berdasarkan user, nama dan hash code
        $useremail=$request->form["donatur_email"]; ; 
        $username=$request->form["donatur_nama"];
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

        #ambil data provinsi, kota dan kecamatan dari raja ongkir
        $donaturprovinsiid=$request->form["donatur_provinsi_id"];
        $donaturkotaid=$request->form["donatur_kota_id"];
        $donaturkecamatanid=$request->form["donatur_kecamatan_id"];
        $kodeposapi=new KodePosAPI;
        $provinsi=$kodeposapi->getProvisiById($donaturprovinsiid);
        $kota=$kodeposapi->getKotaById($donaturkotaid);
        $kecamatan=$kodeposapi->getKecamatanById($donaturkecamatanid);
        $kodepos=$kodeposapi->getKodePosByKotaId($donaturkotaid);

        $donatur=new Donatur;
        $donatur->donatur_kode=$donaturkode;
        $donatur->donatur_email=$request->form["donatur_email"]; 
        $donatur->donatur_nama=$request->form["donatur_nama"];
        $donatur->donatur_nid=$request->form["donatur_nid"];
        $donatur->donatur_gender=$request->form["donatur_gender"];
        $donatur->donatur_agama=$request->form["donatur_agama"];
        $donatur->donatur_telepon=$request->form["donatur_telepon"];
        $donatur->donatur_kerja=$request->form["donatur_kerja"];
        $donatur->donatur_tmp_lahir=$request->form["donatur_tmp_lahir"];
        $donatur->donatur_tgl_lahir=$request->form["donatur_tgl_lahir"];
        $donatur->donatur_alamat=$request->form["donatur_alamat"];
        $donatur->donatur_provinsi_id=$donaturprovinsiid;
        $donatur->donatur_kota_id=$donaturkotaid;
        $donatur->donatur_kecamatan_id=$donaturkecamatanid;
        $donatur->donatur_provinsi=$provinsi;
        $donatur->donatur_kota=$kota;
        $donatur->donatur_kecamatan=$kecamatan;
        $donatur->donatur_kelurahan='';
        $donatur->donatur_kode_pos=$kodepos;
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

        // return redirect()->action('DonaturController@index');
        return response()->json(['status' => 'success', 'message' => 'Penyimpanan Berhasil', 'code' => 200]);

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
            'donatur_provinsi_id'=>'required|string',
            'donatur_kota_id'=>'required|string',
            'donatur_kecamatan_id'=>'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        #ambil data provinsi, kota dan kecamatan dari raja ongkir
        $donaturprovinsiid=$request->get("donatur_provinsi_id");
        $donaturkotaid=$request->get('donatur_kota_id');
        $donaturkecamatanid=$request->get("donatur_kecamatan_id");
        $kodeposapi=new KodePosAPI;
        $provinsi=$kodeposapi->getProvisiById($donaturprovinsiid);
        $kota=$kodeposapi->getKotaById($donaturkotaid);
        $kecamatan=$kodeposapi->getKecamatanById($donaturkecamatanid);
        $kodepos=$kodeposapi->getKodePosByKotaId($donaturkotaid);

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
                    'donatur_kelurahan'=>'',
                    'donatur_kecamatan_id'=>$donaturkecamatanid,
                    'donatur_kota_id'=>$donaturkotaid,
                    'donatur_provinsi_id'=>$donaturprovinsiid,
                    'donatur_kecamatan'=>$kecamatan,
                    'donatur_kota'=>$kota,
                    'donatur_provinsi'=>$provinsi,
                    'donatur_kode_pos' =>$kodepos,
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
            if($dnt->user==null){
                $dnt->donatur_status="Belum Ada Email";
                continue;
            }
            if($dnt->user['email_verified_at']==null){
                $dnt->donatur_status="Belum Konfirmasi Email";
            }else{
                if($dnt->donatur_status=="1"){
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
        }


    }
  
    #------------utility
    public function donaturSimpleList(){
        $donatur=Donatur::where('donatur_status','!=','0')->get();
        return $donatur;
    }

}
