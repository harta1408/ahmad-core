<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\KirimProduk;
use App\Models\Santri;
use App\Models\Produk;
use App\Models\Lembaga;
use App\Models\DonaturSantri;
use App\Models\Bimbingan;
use App\Http\Controllers\BimbinganController;
use Validator;
use Carbon\Carbon;

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
        return view('produk/kirimlist');
    }
    public function load(){
        $kirimproduk=KirimProduk::where('kirim_status','1')->get();
        // foreach ($kirimproduk as $key => $kp) {
        //     if($kp->kirim_status=="1"){
        //         $kp->kirim_status="Dalam Pengiriman";
        //     }
        //     if($kp->kirim_status=="2"){
        //         $kp->kirim_status="Sudah Diterima";
        //     }
        //     if($kp->kirim_status=="3"){
        //         $kp->kirim_status="Dikembalikan";
        //     }
        // }

        return $kirimproduk;
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
        $pendampingid=$donsantri->pendamping_id;

     
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
        Santri::where('id',$santriid)->update(['santri_status'=>'5']);

        #buat pesan ke entitas terkait bahwa produk sedang dikirimkan
        //-----belum jadi

        #simpan bimbingan dengan status masih 0, belum aktif
        #bimbingan bisa aktif otomatis pada saat produk sampai ke santri
        $bimbingan=new Bimbingan;
        $bimbingan->santri_id=$santriid;
        $bimbingan->pendamping_id=$pendampingid;
        $bimbingan->produk_id=$produkid;
        $bimbingan->bimbingan_mulai=date("Y-m-d"); //diganti tanggal sampai
        $bimbingan->bimbingan_berakhir=date("Y-m-d"); //diganti tanggal sampai + produk masa bimbingan
        $bimbingan->bimbingan_nilai_angka='0';
        $bimbingan->bimbingan_nilai_huruf='0';
        $bimbingan->bimbingan_predikat='BELAJAR';
        $bimbingan->bimbingan_catatan='';
        $bimbingan->bimbingan_status='0'; //belum aktif
        $bimbingan->save();

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
        $noresi="";
        $kirimstatus="";
        if(isset($request->kirim_no_resi)){ //no resi dikirim
            $noresi=$request->kirim_no_resi;
            KirimProduk::where('id',$id)->update(['kirim_no_resi'=>$noresi]);
        }

        if(isset($request->kirim_status)){ //status tidak di perbaharui
            if(!isset($request->kirim_tanggal_terima)){
                return response()->json(['status' => 'error', 'message' => 'Tanggal Sampai Harus di Isi', 'code' => 200]);
            }

            $tglterima=$request->kirim_tanggal_terima;
           
            $kirimstatus=$request->kirim_status;
            $kirimproduk=KirimProduk::where('id',$id)->first();
            $santriid=$kirimproduk->santri_id;

            $bimbingancontrol=new BimbinganController;
            $bimbingancontrol->bimbinganSantriMulai($santriid,$tglterima);
            KirimProduk::where('id',$id)->update(['kirim_tanggal_terima'=>$tglterima,'kirim_status'=>'2']);

        }
        return response()->json(['status' => 'success', 'message' => 'Berhasil di perbaharui', 'code' => 200]);
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
