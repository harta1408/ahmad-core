<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\KirimProduk;
use App\Models\KirimManifest;
use App\Models\Santri;
use App\Models\Pendamping;
use App\Models\Produk;
use App\Models\Lembaga;
use App\Models\DonaturSantri;
use App\Models\Bimbingan;
use App\Models\User;
use App\Http\Controllers\Service\BimbinganService;
use App\Http\Controllers\Service\MessageService;
use App\Http\Controllers\Service\KirimProdukService;
use Validator;
use Carbon\Carbon;
use Config;

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
        $kurir[]= array('kurir_id' =>'jne','kurir_nama'=>'JNE');
        $kurir[]= array('kurir_id' =>'pos','kurir_nama'=>'POS Indonesa');
        $kurir[]= array('kurir_id' =>'tiki','kurir_nama'=>'TIKI');
        $kurir[]= array('kurir_id' =>'rpx','kurir_nama'=>'RPX');
        $kurir[]= array('kurir_id' =>'pandu','kurir_nama'=>'Pandu');
        $kurir[]= array('kurir_id' =>'wahana','kurir_nama'=>'Wahana');
        $kurir[]= array('kurir_id' =>'sicepat','kurir_nama'=>'Si Cepat');
        $kurir[]= array('kurir_id' =>'jnt','kurir_nama'=>'JNT');
        $kurir[]= array('kurir_id' =>'pahala','kurir_nama'=>'Pahala');
        $kurir[]= array('kurir_id' =>'sap','kurir_nama'=>'SAP');
        $kurir[]= array('kurir_id' =>'jet','kurir_nama'=>'JET');
        $kurir[]= array('kurir_id' =>'indah','kurir_nama'=>'Indah');
        $kurir[]= array('kurir_id' =>'dse','kurir_nama'=>'DSE');
        $kurir[]= array('kurir_id' =>'slis','kurir_nama'=>'SLIS');
        $kurir[]= array('kurir_id' =>'first','kurir_nama'=>'First');
        $kurir[]= array('kurir_id' =>'ncs','kurir_nama'=>'NCS');
        $kurir[]= array('kurir_id' =>'star','kurir_nama'=>'Star');
        $kurir[]= array('kurir_id' =>'ninja','kurir_nama'=>'Ninja');
        $kurir[]= array('kurir_id' =>'lion','kurir_nama'=>'Lion');
        $kurir[]= array('kurir_id' =>'idl','kurir_nama'=>'IDL');
        $kurir[]= array('kurir_id' =>'rex','kurir_nama'=>'REX');
        $kurir[]= array('kurir_id' =>'ide','kurir_nama'=>'IDE');
        $kurir[]= array('kurir_id' =>'sentral','kurir_nama'=>'Sentral');
        $kurir[]= array('kurir_id' =>'anteraja','kurir_nama'=>'Anter Aja');

        $kurir=json_encode($kurir);

        $santri=Santri::where('santri_status','5')->get();

        return view ('produk/kirimnewindex',compact('santri','kurir'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $santriid=$request->get('santriid');
        $kuririd=$request->get('kuririd');
        $kurir=$request->get('kurir');
        $noseri=$request->get('noseri');
        $tanggalkirim=$request->get('tglkirim');
        $ongkir=$request->get('biaya');
        
        $santrikode=$request->get('form')['santri_kode'];
        $santri=Santri::where('santri_kode',$santrikode)->first();
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
        $kirimproduk->kirim_produk_no_seri=$noseri;
        $kirimproduk->kirim_nama=$lembaga->lembaga_nama;
        $kirimproduk->kirim_telepon=$lembaga->lembaga_telepon;
        $kirimproduk->kirim_no_resi='';
        $kirimproduk->kirim_kurir_id=$kuririd;
        $kirimproduk->kirim_kurir=$kurir;
        $kirimproduk->kirim_tanggal_kirim=$tanggalkirim;
        $kirimproduk->kirim_biaya=$ongkir;

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
        Santri::where('id',$santriid)->update(['santri_status'=>'6']);

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

        #buat pesan ke entitas terkait bahwa produk sedang dikirimkan
        $msg=new MessageService;
        $pengirim='0'; //dari sistem

        #kirim pesan untuk santri
        $santriemail=$santri->santri_email;
        $tujuan=User::where('email',$santriemail)->first()->id;
        $isi='Produk telah dikirimkan melalui '.$kurir.' Tanggal '.$tanggalkirim;
        $msg->saveNotification($pengirim,$tujuan,$isi);

        return response()->json(['status' => 'success', 'message' => 'Berhasil disimpan', 'code' => 200]);
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

            $bimbinganserv=new BimbinganService;
            $bimbinganserv->bimbinganSantriMulai($santriid,$tglterima);
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

    public function main(Request $request){
        $santriid=$request->get('santri_id');
        $kuririd=$request->get('kirim_kurir');
        $tglkirim=$request->get('kirim_tanggal_kirim');
        $noseri=$request->get('kirim_produk_no_seri');


        $santri=Santri::where('id',$santriid)->first();
        $kecamatanid=$santri->santri_kecamatan_id;

        if($kecamatanid==""){
            // $santri=Santri::where('id',$id)->first();  
            return view ('santri/santriupdate',compact('santri'));
            // return response()->json(['status' => 'error', 'message' => 'Data kecamatan tidak ada, silakan dilengkapi', 'code' => 200]);
        }

        $lembaga=Lembaga::where('lembaga_id','ahmad')->first()->lembaga_kota_id;
        $produkberat=Produk::where('id','1')->first()->produk_berat;

        
        $parameter="origin=".$lembaga."&originType=city&destination=".$kecamatanid.
                   "&destinationType=subdistrict&weight=".$produkberat."&courier=".$kuririd;

        $kirimps=new KirimProdukService;
        $biaya=$kirimps->hitungbiayakirim($parameter);

        $costs=json_encode($biaya[0]->costs);
        $kurir=$biaya[0]->name;        

        return view ('produk/kirimnewkonfirmasi',compact('santri','kuririd','kurir','tglkirim','noseri','costs'));
    }
    public function lacakload(){
        $kirimproduk=KirimProduk::whereIn('kirim_status',['1','2'])->get();
        return $kirimproduk;
    }
    public function lacakindex(){
        return view('produk/kirimlacakindex');
    }
    public function lacakmain(Request $request){
        $kirimid=$request->get('kirim_id');
        $kirimproduk=KirimProduk::where('id',$kirimid)->first();
        $noresi=$kirimproduk->kirim_no_resi;
        $kuririd=$kirimproduk->kirim_kurir_id;

        if(!$noresi){
            return response()->json(['status' => 'error', 'message' => 'Nomor Resi tidak tersedia, tidak dapat dilacak', 'code' => 404]);
        }

        $kirimps=new KirimProdukService;
        $result=$kirimps->lacakPengiriman($noresi,$kuririd);
        if($result=='ERROR'){
            return response()->json(['status' => 'error', 'message' => 'Tidak dapat terhubung dengan Raja Ongkir', 'code' => 404]);
        }

        $manifest=$result->rajaongkir->result->manifest;
        $delivered=$result->rajaongkir->result->delivered;

        $ckirimmanifest=KirimManifest::where('kirim_produk_id',$kirimid)->count('kirim_produk_id');

        #jika belum ada data sebelumnya maka buat sejumlah manifes yang diterima dari
        #rajaongkir
        if($ckirimmanifest==0){
            for ($i=0; $i <count($manifest) ; $i++) { 
                $kirimmanifest=new KirimManifest;
                $kirimmanifest->kirim_produk_id=$kirimid; 
                $kirimmanifest->kirim_manifest_code=$manifest[$i]->manifest_code; 
                $kirimmanifest->kirim_manifest_no_resi=$noresi; 
                $kirimmanifest->kirim_manifest_kurir=$kuririd; 
                $kirimmanifest->kirim_manifest_tanggal=$manifest[$i]->manifest_date; 
                $kirimmanifest->kirim_manifest_waktu=$manifest[$i]->manifest_time; 
                $kirimmanifest->kirim_manifest_deskripsi=$manifest[$i]->manifest_description; 
                $kirimmanifest->kirim_manifest_kota=$manifest[$i]->city_name; 
                $kirimmanifest->save();
            }
        }
        #jika ukuran data tidak sama, dihapus dalam database, ganti dengan dari rajaongkir
        if($ckirimmanifest!=count($manifest)){
            KirimManifest::where('kirim_produk_id',$kirimid)->delete();
            for ($i=0; $i <count($manifest) ; $i++) { 
                $kirimmanifest=new KirimManifest;
                $kirimmanifest->kirim_produk_id=$kirimid; 
                $kirimmanifest->kirim_manifest_code=$manifest[$i]->manifest_code; 
                $kirimmanifest->kirim_manifest_no_resi=$noresi; 
                $kirimmanifest->kirim_manifest_kurir=$kuririd; 
                $kirimmanifest->kirim_manifest_tanggal=$manifest[$i]->manifest_date; 
                $kirimmanifest->kirim_manifest_waktu=$manifest[$i]->manifest_time; 
                $kirimmanifest->kirim_manifest_deskripsi=$manifest[$i]->manifest_description; 
                $kirimmanifest->kirim_manifest_kota=$manifest[$i]->city_name; 
                $kirimmanifest->save();
            }
        }
        if($delivered==true){
            $tanggalterima=KirimManifest::where('kirim_produk_id',$kirimid)->orderBy('kirim_manifest_tanggal','desc')->first()->kirim_manifest_tanggal;
            KirimProduk::where('id',$kirimid)->update(['kirim_status'=>'2','kirim_tanggal_terima'=>$tanggalterima]);
            $santriid=$kirimproduk->santri_id;
            $bimbinganserv=new BimbinganService;
            $bimbinganserv->bimbinganSantriMulai($santriid,$tanggalterima);
        }
        return response()->json(['status' => 'success', 'message' => 'Berhasil di perbaharui', 'code' => 200]);
    }

    public function lacakhasil($idkirimproduk){
        $kirimproduk=KirimProduk::with('santri','manifest')->where('id',$idkirimproduk)->first();
        return view('produk/kirimlacakhasil',compact('kirimproduk'));
    }
    
}
