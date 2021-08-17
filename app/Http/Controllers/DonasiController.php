<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Models\Donatur;
use App\Models\Santri;
use App\Models\User;
use App\Models\Pendamping;
use App\Models\DonasiCicilan;
use App\Http\Controllers\Service\DonasiService;
use App\Http\Controllers\Service\MessageService;
use App\Http\Controllers\Service\AccountService;
use DB;

class DonasiController extends Controller
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

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
     
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
        //
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

    #daftar donasi yang sedang menunggu pembayaran, bisa di override jika
    #pembayaran otomatis gagal
    public function donasiPendingIndex(){
        // echo ("Tunggu sedang proses data \n");
        // $accservice=new AccountService;
        // $result=$accservice->mootaDaftarBank();
        // $norekening=$result->data[0]->account_number; 
        // $atasnama=$result->data[0]->atas_nama;
        // $saldo=$result->data[0]->balance;

        // echo ("Nomor Rekening    :".$norekening." \n");
        // echo ("Pemiliki Rekening :".$atasnama." \n");
        // echo ("Saldo             :".$saldo." \n");

        return view('donasi/donasipending');
 
    }
    public function donasiPendingList(){
        return view('donasi/donasipending');
    }
    public function donasiPendingLoad(){
        $todaydate=date("Y-m-d").' 00:00:00';
        $donasicicilan=DonasiCicilan::with('donasi.donatur')
            ->where([['cicilan_jatuh_tempo','<=',$todaydate],['cicilan_status','1']])->get();
        foreach ($donasicicilan as $key => $dc) {
            #jika belum ada, buat tabel pembayaran untuk ciclan tsb
            $cicilanid=$dc->id;
            $donasiservice=new DonasiService;
            $donasiservice->bayarBuatTagihanHarian($cicilanid);
        }
        return $donasicicilan;
    }
    public function donasiPendingUpdate(Request $request, $id){
        $tglbayar="";
        $cicilanstatus="";
        if(isset($request->cicilan_status)){ //status di ubah dikirim
            if(!isset($request->cicilan_tanggal_bayar)){
                return response()->json(['status' => 'error', 'message' => 'Tanggal Bayar Harus di Isi', 'code' => 200]);
            }
            $tglbayar=$request->cicilan_tanggal_bayar;
            $cicilanstatus=$request->cicilan_status;
            DonasiCicilan::where('id',$id)->update(['cicilan_status'=>$cicilanstatus]);

            #update status pembayaran cicilan
            $donasiservice=new DonasiService;
            $donasiservice->bayarCicilan($id,$tglbayar);
            
            return response()->json(['status' => 'success', 'message' => 'Berhasil di perbaharui', 'code' => 200]);
        }
        
    }

    #modul untuk menanangani random santri
    public function donasiRandomIndex(){
        //ambil santri yang belum mendapatkan donasi
        $santritersedia=Santri::where('santri_status','4')->count('id'); 
        $pendampingtersedia=Pendamping::whereIn('pendamping_status',['4','5'])->count('id');
        return view('donasi/donasirandomindex',compact('santritersedia','pendampingtersedia'));
    }
    public function donasiRandomLoad(){
        //ambil data donasi dengan cara bayar tunai=4 dan random santri=1 dan status 3, sudah bayar lunas
        //dan belum pernah di proses sebelumnya, periksa apakah jumlah sisa sudah=0
        $donasi=Donasi::with('donatur')->where([['donasi_cara_bayar','4'],['donasi_random_santri','1'],['donasi_status','3']])->get();
        return $donasi;
    }
    public function donasiRandomMain(Request $request){
        $arrDonasi=array();
        $pjg=strlen($request->donasi_selected);
        $donasiselected=substr($request->donasi_selected,0,$pjg-1);

        $donasiselected=explode(",",$donasiselected);
        $donasi=Donasi::whereIn('id',$donasiselected)->get();
        #hitung jumlah santri yang di butuhkan
        $jmlsantridonasi=Donasi::whereIn('id',$donasiselected)->sum('donasi_jumlah_santri');
        //jika sudah ada data donatur untuk donasi dan santri yang di maksud, maka tidak perlu 
        //melanjutkan proses
        // $jmlsantripenerima=DB::table('donatur_santri')->count('donatur_id');
        // if($jmlsantridonasi<=$jmlsantripenerima){
        //     return response()->json(['status' => 'error', 'message' => 'Donasi sudah tersalurkan sesuai jumlah santri', 'code' => 404]);
        // }

        #hitung jumlah santri yang ada
        $jmlsantri=Santri::where('santri_status',4)->count();
        if($jmlsantri<$jmlsantridonasi){
            //kekurangan santri
            return response()->json(['status' => 'error', 'message' => 'Jumlah Santri tidak mencukupi', 'code' => 404]);
        }

        #ambil santri aktif dengan data lengkap dan belum pernah
        #mengikuti bimbingan
        $santri=Santri::where('santri_status','4')->pluck('id')->toArray();
        $randomsantri=array_rand($santri,$jmlsantridonasi);
        
        #ambil pendamping aktif dengan data lengkap, baik belum membimbing maupun
        #sedang membimbing
        $pendamping=Pendamping::whereIn('pendamping_status',['4','5'])->pluck('id')->toArray();
        if(!$pendamping){
            return response()->json(['status' => 'error', 'message' => 'Tidak ada Pendamping yang tersedia', 'code' => 404]);
        }

        #masukan data dummy
        if($jmlsantridonasi==1){
            foreach ($donasi as $key => $dns) {    
                //pilih pendamping secara acak
                $randompendamping=array_rand($pendamping,1);
                $pendampingid=Pendamping::where('id',$pendamping[$randompendamping])->first()->id;
                $pendampingnama=Pendamping::where('id',$pendamping[$randompendamping])->first()->pendamping_nama;

                $donaturid=$dns->donatur_id;
                $donaturnama=Donatur::where('id',$donaturid)->first()->donatur_nama;
                $santriid=Santri::where('id',$santri[$randomsantri])->first()->id;
                $santrinama=Santri::where('id',$santri[$randomsantri])->first()->santri_nama;
                $arrDonasi[]= array('id' => $dns->id,
                    'donasi_no' => $dns->donasi_no,
                    'donatur_id'=>$donaturid,
                    'donasi_donatur_nama' =>$donaturnama,
                    'donasi_santri_id' => $santriid,
                    'donasi_santri_nama' => $santrinama,
                    'donasi_pendamping_id' => $pendampingid,
                    'donasi_pendamping_nama' => $pendampingnama,
                );
            }
        }else{
            $j=0;
            foreach ($donasi as $key => $dns) {    
                $randompendamping=array_rand($pendamping,1);
                $pendampingid=Pendamping::where('id',$pendamping[$randompendamping])->first()->id;
                $pendampingnama=Pendamping::where('id',$pendamping[$randompendamping])->first()->pendamping_nama;

                $jumlahdonasi=$dns->donasi_sisa_santri;  //ambil santri yang belum mendapat produk
                $donaturid=$dns->donatur_id;
                $donaturnama=Donatur::where('id',$donaturid)->first()->donatur_nama;
                for ($i=0; $i < $jumlahdonasi; $i++) { 
                    //pilih pendamping secara acak
                    $randompendamping=array_rand($pendamping,1);
                    $pendampingid=Pendamping::where('id',$pendamping[$randompendamping])->first()->id;
                    $pendampingnama=Pendamping::where('id',$pendamping[$randompendamping])->first()->pendamping_nama;

                    $santriid=Santri::where('id',$santri[$randomsantri[$j]])->first()->id;
                    $santrinama=Santri::where('id',$santri[$randomsantri[$j]])->first()->santri_nama;
                    $arrDonasi[]= array('id' => $dns->id,
                        'donasi_no' => $dns->donasi_no,
                        'donatur_id'=>$donaturid,
                        'donasi_donatur_nama' =>$donaturnama,
                        'donasi_santri_id' => $santriid,
                        'donasi_santri_nama' => $santrinama,
                        'donasi_pendamping_id' => $pendampingid,
                        'donasi_pendamping_nama' => $pendampingnama,
                    );
                    $j++;
                }
            }
        }

        $donasi=json_encode($arrDonasi);

        #ambil data santri dengan status 4 untuk kemungkinan penggantian santri
        $santri=Santri::where('santri_status','4')->get();
        return view('donasi/donasirandomkonfirmasi',compact('donasi','santri'));

        return response()->json(['status' => 'success', 'message' => 'Penyimpanan berhasil', 'code' => 200]);
    }

    public function donasiRandomSave(Request $request){
        $jmlsantridonasi=count($request->dataDonasi);
        $msg=new MessageService;
        $pengirim='0'; //dari sistem

        #jika hanya satu record returnya bukan array
        if($jmlsantridonasi==1){
            #simpan data donasi santri
            $donasi=$request->get('dataDonasi')[0];
            $donaturid=$donasi['donatur_id'];
            $santriid=$donasi['donasi_santri_id'];
            $pendampingid=$donasi['donasi_pendamping_id'];
            $donatur=Donatur::where('id',$donaturid)->first();
            $donatur->santri()->attach(['donatur_id'=>$donaturid],[
                'santri_id' =>$santriid,
                'pendamping_id' => $pendampingid,
                'donasi_id' =>$donasi['id'],
                'donatur_santri_status' =>'1', //aktif
            ]);
            #update status santri sudah menerima donasi
            Santri::where('id',$santriid)->update(['santri_status'=>'5']);
            $santri=Santri::where('id',$santriid)->first();
            #update donasi sudah tersalurkan ke santri
            Donasi::where('id',$donasi['id'])->update(['donasi_status'=>'5','donasi_sisa_santri'=>0]);
            $donasi=Donasi::where('id',$donasi['id'])->first();

            $pendamping=Pendamping::where('id',$pendampingid)->first();

            #kirimkan pesan
            #kirim pesan ke donatur, santri dan pendamping bahwa produk telah diterima (belum aktif)
            $santrinama=$santri->santri_nama;
            $donaturemail=$donatur->donatur_email;
            $santriemail=$santri->santri_email;
            $pendampingemail=$pendamping->pendamping_email;

            #pesan untuk donatur
            $tujuan=User::where('email',$donaturemail)->first()->id;
            $isi='Donasi anda No '.$donasi->donasi_no.' telah disalurkan kepada Santri '.$santrinama;
            $msg->saveNotification($pengirim,$tujuan,$isi);

            #pesan untuk santri
            $tujuan=User::where('email',$santriemail)->first()->id;
            $isi='Selamat, anda telah terpilih menjadi Santri, Produk segera kami kirimkan' ;
            $msg->saveNotification($pengirim,$tujuan,$isi);

            #pesan untuk pendamping
            $tujuan=User::where('email',$pendampingemail)->first()->id;
            $isi='Santri atas nama '.$santrinama.' telah terpilih di bawah bimbingan Anda, produk segera dikirimkan';
            $msg->saveNotification($pengirim,$tujuan,$isi);
        }else{
            #periksa apakah ada data santri yang ganda
            for ($i=0; $i < $jmlsantridonasi; $i++) { 
                $santriid=$request->get('dataDonasi')[$i]['donasi_santri_id'];
                for ($j=$i+1; $j < $jmlsantridonasi; $j++) { 
                    $santricek=$request->get('dataDonasi')[$j]['donasi_santri_id'];
                    if($santriid==$santricek){
                        return response()->json(['status' => 'error', 'message' => 'Tidak dapat dilanjutkan, ada data santri yang sama', 'code' => 404]);
                    }
                }
            }
            for ($i=0; $i < count($request->dataDonasi); $i++) { 
                #simpan data donasi santri
                $id=$request->get('dataDonasi')[$i]['id'];
                $donaturid=$request->get('dataDonasi')[$i]['donatur_id'];
                $santriid=$request->get('dataDonasi')[$i]['donasi_santri_id'];
                $pendampingid=$request->get('dataDonasi')[$i]['donasi_pendamping_id'];
                $donatur=Donatur::where('id',$donaturid)->first();
                $donatur->santri()->attach(['donatur_id'=>$donaturid],[
                    'santri_id' =>$santriid,
                    'donasi_id' =>$id,
                    'pendamping_id' => $pendampingid,
                    'donatur_santri_status' =>'1', //aktif
                ]);
                #update status santri sudah menerima donasi
                Santri::where('id',$santriid)->update(['santri_status'=>'5']);
                $santri=Santri::where('id',$santriid)->first();
                #update donasi sudah tersalurkan ke santri
                $sisasantri=Donasi::where('id',$id)->first()->donasi_sisa_santri-1;
                $donasistatus='3';
                if($sisasantri==0){
                    $donasistatus='5'; //jika sudah tidak ada santri berarti sudah selesai
                }
                Donasi::where('id',$id)->update(['donasi_status'=>$donasistatus,'donasi_sisa_santri'=>$sisasantri]);
                $donasi=Donasi::where('id',$id)->first();

                $pendamping=Pendamping::where('id',$pendampingid)->first();

                #kirimkan pesan
                #kirim pesan ke donatur, santri dan pendamping bahwa produk telah diterima (belum aktif)
                $santrinama=$santri->santri_nama;
                $donaturemail=$donatur->donatur_email;
                $santriemail=$santri->santri_email;
                $pendampingemail=$pendamping->pendamping_email;

                #pesan untuk donatur
                $tujuan=User::where('email',$donaturemail)->first()->id;
                $isi='Donasi anda No '.$donasi->donasi_no.' telah disalurkan kepada Santri '.$santrinama;
                $msg->saveNotification($pengirim,$tujuan,$isi);
    
                #pesan untuk santri
                $tujuan=User::where('email',$santriemail)->first()->id;
                $isi='Selamat, anda telah terpilih menjadi Santri, Produk segera kami kirimkan' ;
                $msg->saveNotification($pengirim,$tujuan,$isi);
    
                #pesan untuk pendamping
                $tujuan=User::where('email',$pendampingemail)->first()->id;
                $isi='Santri atas nama '.$santrinama.' telah terpilih di bawah bimbingan Anda, produk segera dikirimkan';
                $msg->saveNotification($pengirim,$tujuan,$isi);
            };
        }
        return response()->json(['status' => 'success', 'message' => 'Produk Berhasil di Distribusikan', 'code' => 200]);
    }

    #mutasi bank
    public function mutasiBankIndex(){
        $accserv=new AccountService;
        $daftarbank=$accserv->mootaDaftarBank()->data;
        return view('donasi/mutasibankindex',compact('daftarbank'));
    }
    public function mutasiBankDetail(Request $request){
        $bankid=$request->bank_id;
        $accserv=new AccountService;
        $mutasi=$accserv->mootaGetMutasiByBank($bankid)->data;

        return view('donasi/mutasibankdetail',compact('mutasi'));

        
    }
    #------------utility
    public function donasiByDonaturId($donaturid){
        $donasi=Donasi::where('donatur_id',$donaturid)->get();
        return $donasi;
    }

}
