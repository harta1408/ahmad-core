<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Bimbingan; 
use App\Models\Produk;
use App\Models\Donatur;
use App\Models\Santri;
use App\Models\Pendamping;
use App\Models\DonaturSantri;
use App\Models\User;
use App\Http\Controllers\Service\MessageService;

class BimbinganController extends Controller
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
        return view('bimbingan/bimbinganlist');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bimbingan=Bimbingan::with('santri','pendamping','produk')->whereIn('bimbingan_status',['0','1'])->get();
        foreach ($bimbingan as $key => $bm) {
            $status=$bm->bimbingan_status;
            if($status=='0'){
                $bm->bimbingan_status='Menunggu';
            }
            if($status=='1'){
                $bm->bimbingan_status='Dalam Bimbingan';
            }
        }
        return $bimbingan;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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


    public function bimbinganSantriMulai($santriid,$mulai){
        #proses bimbingan katika produk sampai di santri
        $produkid=Bimbingan::where('santri_id',$santriid)->first()->produk_id;
        $dayno=Produk::where('id',$produkid)->first()->produk_masa_bimbingan;
        $santri=Santri::where('id',$santriid)->first();

        #hitung penambahan tanggal untuk menentukan tanggal akhir
        $akhir=date('Y-m-d',strtotime($mulai.' '.$dayno." days"));

        #pebaharui data
        Bimbingan::where('santri_id',$santriid)->update([
            'bimbingan_mulai'=>$mulai,
            'bimbingan_berakhir' =>$akhir,
            'bimbingan_status'=>'1']);
        $pendampingid=Bimbingan::where('santri_id',$santriid)->first()->pendamping_id;
        $donaturid=DonaturSantri::where([['santri_id',$santriid],['pendamping_id',$pendampingid]])->first()->donatur_id;
        $donaturemail=Donatur::where('id',$donaturid)->first()->donatur_email;
        $santriemail=$santri->santri_email;
        $pendampingemail=Pendamping::where('id',$pendampingid)->first()->pendamping_email;

        #update status santri dan pendamping dalam bimbingan
        Santri::where('id',$santriid)->update(['santri_status'=>'6']);
        Pendamping::where('id',$pendampingid)->update(['pendamping_status'=>'5']);

        #kirim pesan ke donatur, santri dan pendamping bahwa produk telah diterima (belum aktif)
        $msg=new MessageService;
        $pengirim='0'; //dari sistem
        $santrinama=$santri->santri_nama;

        #pesan untuk donatur
        $tujuan=User::where('email',$donaturemail)->first()->id;
        $isi='Bimbingan santri atas nama '.$santrinama.' telah dimulai';
        $msg->saveNotification($pengirim,$tujuan,$isi);

        #pesan untuk santri
        $tujuan=User::where('email',$santriemail)->first()->id;
        $isi='Produk sudah diterima, silakan untuk memulai bimbingan' ;
        $msg->saveNotification($pengirim,$tujuan,$isi);

        #pesan untuk pendamping
        $tujuan=User::where('email',$pendampingemail)->first()->id;
        $isi='Bimbingan santri atas nama '.$santrinama.' telah dimulai, silakan untuk ditindaklanjuti';
        $msg->saveNotification($pengirim,$tujuan,$isi);
    }
}
