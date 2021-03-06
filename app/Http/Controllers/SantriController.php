<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Santri;
use App\Models\KodePos;
use App\Models\User;
use App\Http\Controllers\KodePosAPI;
use App\Http\Controllers\SantriAPI;
use Validator;
class SantriController extends Controller
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
        $santri=Santri::with('user')->where('santri_status','!=','0')->get();
        foreach ($santri as $key => $snt) {
            if($snt->user==null){
                $snt->santri_status="Belum Ada Email (User Login)";
                continue;
            }
            if($snt->user['email_verified_at']==null){
                $snt->santri_status="Belum Konfirmasi Email";
            }else{
                switch ($snt->santri_status) {
                    case '1';
                        $snt->santri_status="Belum Lengkap";
                        break;
                    case '2':
                        $snt->santri_status="Belum Isi Kuesioner";
                        break;
                    case '3':
                        $snt->santri_status="Belum Otorisasi ";
                        break;
                    case '4':
                        $snt->santri_status="Belum Dapat Produk";
                        break;
                    case '5':
                        $snt->santri_status="Terpilih, menunggu Produk";
                        break;
                    case '6':
                        $snt->santri_status="Dalam bimbingan";
                        break;
                    default:
                        $snt->santri_status="Lulus";
                        break;
                }
            }
        }
        return view('santri/santrilist',compact('santri'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('santri/santrinew');
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
            'santri_email' => 'required|email|unique:santri|max:100',
            'santri_nama' => ['required','string','max:30'],
            'santri_telepon' => 'required|string',
            'santri_alamat'=>'required|string',
            // 'santri_provinsi_id'=>'required|string',
            // 'santri_kota_id'=>'required|string',
            // 'santri_kecamatan_id'=>'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $santriapi=new SantriAPI;
        $santrikode=$santriapi->santriKode();

        #buat hash code acak untuk default yang harus langsung diganti
        #ketika email terverifikasi
        #link verifikasi di panggil berdasarkan user, nama dan hash code
        $useremail=$request->get("santri_email"); ; 
        $username=$request->get("santri_nama");
        $url = public_path();
        $usertipe="1"; //tipe user santri
        $hashcode=md5(rand(100000,999999)); 

        #buat user baru dengan alamat email yang dimasukan
        $user=new User;
        $user->email=$useremail;
        $user->name=$username;
        $user->hash_code=$hashcode; 
        $user->tipe=$usertipe;
        $user->password=Hash::make($santrikode);
        $exec=$user->save();

        #ambil data provinsi, kota dan kecamatan dari raja ongkir
        $santriprovinsiid=$request->get("santri_provinsi_id");
        $santrikotaid=$request->get('santri_kota_id');
        $santrikecamatanid=$request->get("santri_kecamatan_id");
        $kodeposapi=new KodePosAPI;
        $provinsi=$kodeposapi->getProvisiById($santriprovinsiid);
        $kota=$kodeposapi->getKotaById($santrikotaid);
        $kecamatan=$kodeposapi->getKecamatanById($santrikecamatanid);
        $kodepos=$kodeposapi->getKodePosByKotaId($santrikotaid);

        $santri=new santri;
        $santri->santri_kode=$santrikode;
        $santri->santri_email=$request->get("santri_email"); 
        $santri->santri_nama=$request->get("santri_nama");
        $santri->santri_nid=$request->get("santri_nid");
        $santri->santri_gender=$request->get("santri_gender");
        $santri->santri_agama=$request->get("santri_agama");
        $santri->santri_telepon=$request->get("santri_telepon");
        $santri->santri_kerja=$request->get("santri_kerja");
        $santri->santri_tmp_lahir=$request->get("santri_tmp_lahir");
        $santri->santri_tgl_lahir=$request->get("santri_tgl_lahir");
        $santri->santri_alamat=$request->get("santri_alamat");
        $santri->santri_provinsi_id=$santriprovinsiid;
        $santri->santri_kota_id=$santrikotaid;
        $santri->santri_kecamatan_id=$santrikecamatanid;
        $santri->santri_provinsi=$provinsi;
        $santri->santri_kota=$kota;
        $santri->santri_kecamatan=$kecamatan;
        $santri->santri_kelurahan='';
        $santri->santri_kode_pos=$kodepos;
        $santri->santri_status='1'; //aktif belum melengkapi data
        $santri->save();

        // kirim email registrasi
        // $url=$url.'/register'.'/'.$hashcode;
        // $data = array('name'=>$username,'url'=>$url);
        // Mail::send('emailregister', $data, function($message) use($useremail, $username) {
        //    $message->to($useremail, $username)->subject
        //       ('no-reply : Pendaftaran AHMaD Project');
        //    $message->from('ahmad@gimanamas.com','AHMaD Project');
        // });

        return redirect()->action('SantriController@index');
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
            'santri_nama' => 'required|string',
            'santri_telepon' => 'required|string',
            'santri_alamat' => 'required|string',
            'santri_provinsi_id'=>'required|string',
            'santri_kota_id'=>'required|string',
            'santri_kecamatan_id'=>'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        #ambil data provinsi, kota dan kecamatan dari raja ongkir
        $santriprovinsiid=$request->get("santri_provinsi_id");
        $santrikotaid=$request->get('santri_kota_id');
        $santrikecamatanid=$request->get("santri_kecamatan_id");
        $kodeposapi=new KodePosAPI;
        $provinsi=$kodeposapi->getProvisiById($santriprovinsiid);
        $kota=$kodeposapi->getKotaById($santrikotaid);
        $kecamatan=$kodeposapi->getKecamatanById($santrikecamatanid);
        $kodepos=$kodeposapi->getKodePosByKotaId($santrikotaid);

        $exec=santri::where('id','=' ,$id)
            ->update(['santri_nid'=>$request->get('santri_nid'),
                    'santri_nama'=>$request->get('santri_nama'),
                    'santri_tmp_lahir'=>$request->get('santri_tmp_lahir'), 
                    'santri_tgl_lahir'=>$request->get('santri_tgl_lahir'), 
                    'santri_gender'=>$request->get('santri_gender'), 
                    'santri_agama'=>$request->get('santri_agama'), 
                    'santri_telepon'=>$request->get('santri_telepon'), 
                    'santri_kerja'=>$request->get('santri_kerja'),
                    'santri_alamat'=>$request->get('santri_alamat'), 
                    'santri_kecamatan_id'=>$santrikecamatanid,
                    'santri_kota_id'=>$santrikotaid,
                    'santri_provinsi_id'=>$santriprovinsiid,
                    'santri_kecamatan'=>$kecamatan,
                    'santri_kota'=>$kota,
                    'santri_provinsi'=>$provinsi,
                    'santri_kode_pos' =>$kodepos,
                    // 'santri_status' => '2', //data sudah lengkap
                ]);
        return redirect()->action('SantriController@santriRenewIndex');
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
    public function santriRenewIndex(){
        $santri=Santri::with('user')->where('santri_status','!=','0')->get();
        foreach ($santri as $key => $snt) {
            if($snt->user==null){
                $snt->santri_status="Belum Ada User";
                continue;
            }else if($snt->user['email_verified_at']==null){
                $snt->santri_status="Belum Konfirmasi Email";
            }else{
                switch ($snt->santri_status) {
                    case '1':
                        $snt->santri_status="Data Belum Lengkap";
                        break;
                    case '2':
                        $snt->santri_status="Belum Otorisasi";
                        break;
                    case '3':
                        $snt->santri_status="Data Lengkap";
                        break;
                    case '4':
                        $snt->santri_status="Menunggu Produk";
                        break;
                    case '5':
                        $snt->santri_status="Dapat Produk";
                        break;
                    case '6':
                        $snt->santri_status="Dalam bimbingan";
                        break;
                    default:
                        $snt->santri_status="Lulus";
                        break;
                }
            }
        }
        return view('santri/santrirenewindex',compact('santri'));
    }
    public function santriRenewMain(Request $request){
        $id=$request->get('santri_id');
        $status=$request->get('santri_state');

        if($status=="UPDATE"){
            $santri=Santri::where('id',$id)->first();  
            return view ('santri/santriupdate',compact('santri'));
        }else{

        }
    }
    public function santriOtorisasiLoad(){
        $santri=Santri::with('user')->where('santri_status','3')->get();
        return $santri;
    }
    public function santriOtorisasiUpdate(Request $request,$id){
        $exec=Santri::where('id','=' ,$id)->update(['santri_status' => '4']); //data di otorisasi
    }
    public function santriOtorisasiIndex(){
        return view('santri/santriotorisasi');
    }
    
    #------------utility
    public function santriSimpleList(){
        $santri=Santri::where('santri_status','!=','0')->get();
        return $santri;
    }
}
