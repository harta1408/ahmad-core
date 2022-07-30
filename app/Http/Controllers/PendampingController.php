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
use App\Http\Controllers\Service\MessageService;
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
            if($pdmp->user==null){
                $pdmp->pendamping_status="Belum Ada Email (User Login)";
                continue;
            }
            switch ($pdmp->pendamping_status) {
                case '1':
                    $pdmp->pendamping_status="Belum Isi Kuesioner";
                    break;
                case '2':
                    $pdmp->pendamping_status="Belum Otorisasi";
                    break;
                case '3':
                    $pdmp->pendamping_status="Data Lengkap";
                    if($pdmp->user['email_verified_at']==null){
                        $pdmp->pendamping_status="Belum Konfirmasi Email";
                    }
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
        $validator = Validator::make($request->form, [
            'pendamping_email' => 'required|email|unique:pendamping|max:100',
            'pendamping_nama' => 'required|string',
            'pendamping_telepon' => 'required|string',
            'pendamping_alamat' => 'required|string',
            'pendamping_provinsi_id'=>'required|string',
            'pendamping_kota_id'=>'required|string',
            'pendamping_kecamatan_id'=>'required|string',
        ],[
            'pendamping_email.required' => 'Silakan Masukan Alamat Email',
            'pendamping_email.email' => 'Masukan dalam format Alamat Email', 
            'pendamping_email.unique' => 'Alamat Email sudah terdaftar', 
            'pendamping_nama.required' => 'Silakan isi Nama Agniya', 
            'pendamping_telepon.required' => 'Silakan isi Telepon Agniya', 
            'pendamping_alamat.required' => 'Silakan isi Alamat Agniya', 
            'pendamping_provinsi_id.required' => 'Silakan Pilih Propinsi dari daftar', 
            'pendamping_kota_id.required' => 'Silakan Pilih Kota dari Daftar', 
            'pendamping_kecamatan_id.required' => 'Silakan Pilih Kecamatan dari Daftar', 
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $pendampingapi=new PendampingAPI;
        $pendampingkode=$pendampingapi->pendampingKode();

        #buat hash code acak untuk default yang harus langsung diganti
        #ketika email terverifikasi
        #link verifikasi di panggil berdasarkan user, nama dan hash code
        $useremail=$request->form["pendamping_email"]; ; 
        $username=$request->form["pendamping_nama"];
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

        #ambil data provinsi, kota dan kecamatan dari raja ongkir
        $pendampingprovinsiid=$request->form["pendamping_provinsi_id"];
        $pendampingkotaid=$request->form['pendamping_kota_id'];
        $pendampingkecamatanid=$request->form["pendamping_kecamatan_id"];
        $kodeposapi=new KodePosAPI;
        $provinsi=$kodeposapi->getProvisiById($pendampingprovinsiid);
        $kota=$kodeposapi->getKotaById($pendampingkotaid);
        $kecamatan=$kodeposapi->getKecamatanById($pendampingkecamatanid);
        $kodepos=$kodeposapi->getKodePosByKotaId($pendampingkotaid);

        $pendamping=new Pendamping;
        $pendamping->pendamping_kode=$pendampingkode;
        $pendamping->pendamping_email=$request->form["pendamping_email"]; 
        $pendamping->pendamping_nama=$request->form["pendamping_nama"];
        $pendamping->pendamping_nid=$request->form["pendamping_nid"];
        $pendamping->pendamping_gender=$request->form["pendamping_gender"];
        $pendamping->pendamping_agama=$request->form["pendamping_agama"];
        $pendamping->pendamping_telepon=$request->form["pendamping_telepon"];
        $pendamping->pendamping_kerja=$request->form["pendamping_kerja"];
        $pendamping->pendamping_tmp_lahir=$request->form["pendamping_tmp_lahir"];
        $pendamping->pendamping_tgl_lahir=$request->form["pendamping_tgl_lahir"];
        $pendamping->pendamping_honor=$request->form["pendamping_honor"];
        $pendamping->pendamping_status_pegawai=$request->form["pendamping_status_pegawai"];
        $pendamping->pendamping_alamat=$request->form["pendamping_alamat"];
        $pendamping->pendamping_provinsi_id=$pendampingprovinsiid;
        $pendamping->pendamping_kota_id=$pendampingkotaid;
        $pendamping->pendamping_kecamatan_id=$pendampingkecamatanid;
        $pendamping->pendamping_provinsi=$provinsi;
        $pendamping->pendamping_kota=$kota;
        $pendamping->pendamping_kecamatan=$kecamatan;
        $pendamping->pendamping_kelurahan='';
        $pendamping->pendamping_kode_pos=$kodepos;
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
            'pendamping_nama' => ['required','string','max:30'],
            'pendamping_telepon' => 'required|string',
            'pendamping_alamat'=>'required|string',
            'pendamping_provinsi_id'=>'required|string',
            'pendamping_kota_id'=>'required|string',
            'pendamping_kecamatan_id'=>'required|string',
        ]);
     


        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        #ambil data provinsi, kota dan kecamatan dari raja ongkir
        $pendampingprovinsiid=$request->get("pendamping_provinsi_id");
        $pendampingkotaid=$request->get('pendamping_kota_id');
        $pendampingkecamatanid=$request->get("pendamping_kecamatan_id");
        $kodeposapi=new KodePosAPI;
        $provinsi=$kodeposapi->getProvisiById($pendampingprovinsiid);
        $kota=$kodeposapi->getKotaById($pendampingkotaid);
        $kecamatan=$kodeposapi->getKecamatanById($pendampingkecamatanid);
        $kodepos=$kodeposapi->getKodePosByKotaId($pendampingkotaid);

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
                    'pendamping_kecamatan_id'=>$pendampingkecamatanid,
                    'pendamping_kota_id'=>$pendampingkotaid,
                    'pendamping_provinsi_id'=>$pendampingprovinsiid,
                    'pendamping_kecamatan'=>$kecamatan,
                    'pendamping_kota'=>$kota,
                    'pendamping_provinsi'=>$provinsi,
                    'pendamping_kode_pos' =>$kodepos,
                    'pendamping_status' => '4', //data sudah lengkap
                ]);
        return redirect()->action('PendampingController@pendampingUpdateIndex');
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
    public function pendampingUpdateIndex(){
        $pendamping=Pendamping::with('user')->where('pendamping_status','!=','0')->get();
        foreach ($pendamping as $key => $pdmp) {
            if($pdmp->user==null){
                $pdmp->donatur_status="Belum Ada Email";
                continue;
            }
            if($pdmp->user['email_verified_at']==null){
                $pdmp->pendamping_status="Belum Konfirmasi Email";
            }
            switch ($pdmp->pendamping_status) {
                case '1':
                    $pdmp->pendamping_status="Belum Isi Kuesioner";
                    break;
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
        return view('pendamping/pendampingupdateindex',compact('pendamping'));
    }
    public function pendampingUpdateMain(Request $request){
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

        $pendamping=Pendamping::where('id',$id)->first();
        $useremail=$pendamping->pendamping_email;
        $username=$pendamping->pendamping_nama;


        #ambil user berdasarkan email
        $user=User::with('donatur')->where('email',$useremail)->first();
        $hashcode=$user->hash_code;
        $msg=new MessageService;
        #kirim email verifikasi
        $msg->kirimEmailVerifikasi($useremail,$username,$hashcode);
        #simpan/kirim pesan
        $msg->simpanNotifikasiSelamatBergabung('0',$user->id);
    }
    public function pendampingOtorisasiIndex(){
        return view('pendamping/pendampingotorisasi');
    }

    #------------utility
    public function pendampingSimpleList(){
        $pendamping=Pendamping::where('pendamping_status','!=','0')->get();
        return $pendamping;
    }
}
