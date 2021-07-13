<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Pendamping;
use App\Models\KodePos;
use App\Models\User;
use App\Http\Controllers\KodePosAPI;
use App\Http\Controllers\PendampingAPI;
use Validator;
class PendampingController extends Controller
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
        $pendamping=Pendamping::with('user')->where('pendamping_status','!=','0')->get();
        foreach ($pendamping as $key => $pdmp) {
            if($pdmp->user['email_verified_at']==null){
                $pdmp->pendamping_status="Belum Konfirmasi Email";
            }else{
                switch ($pdmp->pendamping_status) {
                    case '1':
                        $pdmp->pendamping_status="Belum lengkap";
                        break;
                    case '2':
                        $pdmp->pendamping_status="Belum Isi Kuesioner";
                        break;
                    case '3':
                        $pdmp->pendamping_status="Belum Otorisasi";
                        break;
                    case '4':
                        $pdmp->pendamping_status="Belum ada Bimbingan";
                        break;
                    case '5':
                        $pdmp->pendamping_status="Membimbing Santri";
                        break;
                    default:
                        $pdmp->pendamping_status="Pensiun";
                        break;
                }
            }
        }
        return view('pendamping/pendampinglist',compact('pendamping'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('pendamping/pendampingnew');
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
            'pendamping_email' => 'required|email|unique:pendamping|max:100',
            'pendamping_nama' => ['required','string','max:30'],
            'pendamping_telepon' => 'required|string',
            'pendamping_alamat'=>'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $pendampingapi=new PendampingAPI;
        $pendampingkode=$pendampingapi->pendampingKode();

        #buat hash code acak untuk default yang harus langsung diganti
        #ketika email terverifikasi
        #link verifikasi di panggil berdasarkan user, nama dan hash code
        $useremail=$request->get("pendamping_email"); ; 
        $username=$request->get("pendamping_nama");
        $url = public_path();
        $usertipe="3"; //tipe user pendamping
        $hashcode=md5(rand(100000,999999)); 

        #buat user baru dengan alamat email yang dimasukan
        $user=new User;
        $user->email=$useremail;
        $user->name=$username;
        $user->hash_code=$hashcode; 
        $user->tipe=$usertipe;
        $user->password=Hash::make($pendampingkode);
        $exec=$user->save();

        $pendamping=new Pendamping;
        $pendamping->pendamping_kode=$pendampingkode;
        $pendamping->pendamping_email=$request->get("pendamping_email"); 
        $pendamping->pendamping_nama=$request->get("pendamping_nama");
        $pendamping->pendamping_nid=$request->get("pendamping_nid");
        $pendamping->pendamping_gender=$request->get("pendamping_gender");
        $pendamping->pendamping_agama=$request->get("pendamping_agama");
        $pendamping->pendamping_telepon=$request->get("pendamping_telepon");
        $pendamping->pendamping_kerja=$request->get("pendamping_kerja");
        $pendamping->pendamping_tmp_lahir=$request->get("pendamping_tmp_lahir");
        $pendamping->pendamping_tgl_lahir=$request->get("pendamping_tgl_lahir");
        $pendamping->pendamping_honor=$request->get("pendamping_honor");
        $pendamping->pendamping_status_pegawai=$request->get("pendamping_status_pegawai");
        $pendamping->pendamping_alamat=$request->get("pendamping_alamat");
        $pendamping->pendamping_provinsi=$request->get("pendamping_provinsi");
        $pendamping->pendamping_kota=$request->get("pendamping_kota");
        $pendamping->pendamping_kecamatan=$request->get("pendamping_kecamatan");
        $pendamping->pendamping_kelurahan=$request->get("pendamping_kelurahan");
        $pendamping->pendamping_kode_pos=$request->get("pendamping_kode_pos");
        $pendamping->pendamping_status='1'; //aktif belum melengkapi data
        $pendamping->save();

        // kirim email registrasi
        // $url=$url.'/register'.'/'.$hashcode;
        // $data = array('name'=>$username,'url'=>$url);
        // Mail::send('emailregister', $data, function($message) use($useremail, $username) {
        //    $message->to($useremail, $username)->subject
        //       ('no-reply : Pendaftaran AHMaD Project');
        //    $message->from('ahmad@gimanamas.com','AHMaD Project');
        // });

        return redirect()->action('PendampingController@index');
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
            'pendamping_nama' => 'required|string',
            'pendamping_telepon' => 'required|string',
            'pendamping_alamat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $exec=Pendamping::where('id','=' ,$id)
            ->update(['pendamping_nid'=>$request->get('pendamping_nid'),
                    'pendamping_nama'=>$request->get('pendamping_nama'),
                    'pendamping_tmp_lahir'=>$request->get('pendamping_tmp_lahir'), 
                    'pendamping_tgl_lahir'=>$request->get('pendamping_tgl_lahir'), 
                    'pendamping_gender'=>$request->get('pendamping_gender'), 
                    'pendamping_agama'=>$request->get('pendamping_agama'), 
                    'pendamping_telepon'=>$request->get('pendamping_telepon'), 
                    'pendamping_kerja'=>$request->get('pendamping_kerja'),
                    'pendamping_alamat'=>$request->get('pendamping_alamat'), 
                    'pendamping_kode_pos'=>$request->get('pendamping_kode_pos'),
                    'pendamping_kelurahan'=>$request->get('pendamping_kelurahan'),
                    'pendamping_kecamatan'=>$request->get('pendamping_kecamatan'),
                    'pendamping_kota'=>$request->get('pendamping_kota'),
                    'pendamping_provinsi'=>$request->get('pendamping_provinsi'),
                    'pendamping_kode_pos' =>$request->get('pendamping_kode_pos'),
                    'pendamping_status' => '4', //data sudah lengkap
                ]);
        return redirect()->action('PendampingController@pendampingRenewIndex');
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
    public function pendampingRenewIndex(){
        $pendamping=Pendamping::with('user')->where('pendamping_status','!=','0')->get();
        foreach ($pendamping as $key => $pdmp) {
            if($pdmp->user['email_verified_at']==null){
                $pdmp->pendamping_status="Belum Konfirmasi Email";
            }else{
                switch ($pdmp->pendamping_status) {
                    case '2':
                        $pdmp->pendamping_status="Belum Otorisasi";
                        break;
                    case '3':
                        $pdmp->pendamping_status="Data Lengkap";
                        break;
                    case '4':
                        $pdmp->pendamping_status="Menunggu Produk";
                        break;
                    case '5':
                        $pdmp->pendamping_status="Dapat Produk";
                        break;
                    case '6':
                        $pdmp->pendamping_status="Dalam bimbingan";
                        break;
                    default:
                        $pdmp->pendamping_status="Lulus";
                        break;
                }
            }
        }
        return view('pendamping/pendampingrenewindex',compact('pendamping'));
    }
    public function pendampingRenewMain(Request $request){
        $id=$request->get('pendamping_id');
        $status=$request->get('pendamping_state');

        if($status=="UPDATE"){
            $pendamping=Pendamping::where('id',$id)->first();  
            return view ('pendamping/pendampingupdate',compact('pendamping'));
        }else{

        }
    }
    public function pendampingOtorisasiLoad(){
        $pendamping=Pendamping::with('user')->where('pendamping_status','2')->get();
        return $pendamping;
    }
    public function pendampingOtorisasiUpdate(Request $request,$id){
        $exec=Pendamping::where('id','=' ,$id)->update(['pendamping_status' => '3']); //data di otorisasi
    }
    public function pendampingOtorisasiIndex(){
        return view('pendamping/pendampingotorisasi',compact('pendamping'));
    }

    #------------utility
    public function pendampingSimpleList(){
        $pendamping=Pendamping::where('pendamping_status','!=','0')->get();
        return $pendamping;
    }
}
