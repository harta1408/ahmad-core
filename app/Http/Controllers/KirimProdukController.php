<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\KirimProduk;
use App\Models\Santri;
use App\Models\Produk;
use App\Models\Lembaga;
use App\Models\DonaturSantri;
use Validator;

class KirimProdukController extends Controller
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
        // $donatur=Donatur::with('user')->where('donatur_status','!=','0')->get();
        // foreach ($donatur as $key => $dnt) {
        //     if($dnt->user['email_verified_at']==null){
        //         $dnt->donatur_status="Belum Konfirmasi Email";
        //     }else{
        //         if($dnt->donatur_status=="2"){
        //             $dnt->donatur_status="Belum Lengkap";
        //         }else{
        //             $dnt->donatur_status="Sudah Lengkap";
        //         }
        //     }
        // }
        $kirimproduk=KirimProduk::where('kirim_status','1')->get();
        return view('produk/kirimlist',compact('kirimproduk'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $santri=Santri::where('santri_status','5')->get();
        return view ('produk/kirimnew',compact('santri'));
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
            'santri_id'=>'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        $santriid=$request->get('santri_id');
        $santri=Santri::where('id',$santriid)->first();
        $produkid=Produk::first()->id;

        $donsantri=DonaturSantri::where([['santri_id',$santriid],['donatur_santri_status','1']])->first();
        $donaturid=$donsantri->donatur_id;
        $donasiid=$donsantri->donasi_id;

     
        $lembaga=Lembaga::first();
        if(!$lembaga){
            return response()->json(['status' => 'error', 'message' => 'lembaga belum di definisikan', 'code' => 404]);
        }

        $kirimproduk=new KirimProduk;
        $kirimproduk->produk_id=$produkid;
        $kirimproduk->santri_id=$santriid; 
        $kirimproduk->donatur_id=$donaturid;
        $kirimproduk->donasi_id=$donasiid;
        $kirimproduk->kirim_produk_no_seri=$request->get("kirim_produk_no_seri");
        $kirimproduk->kirim_nama=$lembaga->lembaga_nama;
        $kirimproduk->kirim_telepon=$lembaga->lembaga_telepon;
        $kirimproduk->kirim_no_resi=$request->get("kirim_no_resi");
        $kirimproduk->kirim_tanggal_kirim=$request->get("kirim_tanggal_kirim");
        $kirimproduk->kirim_biaya=$request->get("kirim_biaya");

        $kirimproduk->kirim_penerima_nama=$santri->santri_nama;
        $kirimproduk->kirim_penerima_telepon=$santri->santri_telepon;
        $kirimproduk->kirim_penerima_alamat=$request->santri_alamat;
        $kirimproduk->kirim_penerima_kode_pos=$request->santri_kode_pos;
        $kirimproduk->kirim_penerima_kota=$request->santri_kota;
        $kirimproduk->kirim_penerima_kecamatan=$request->santri_kecamatan;
        $kirimproduk->kirim_penerima_kelurahan=$request->santri_kelurahan;
        $kirimproduk->kirim_status='1'; //aktif belum melengkapi data
        $kirimproduk->save();

        #update status santri sendang menunggu produk
        Santri::where('id',$santriid)->update(['santri_status','5']);

        return redirect()->action('KirimProdukController@index');
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
