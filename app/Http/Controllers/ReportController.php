<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonasiCicilan;
use App\Models\Donasi;
use App\Models\Santri;
use App\Models\Bimbingan;
use PDF;
class ReportController extends Controller
{
    #report donasi harian
    public function reportDonasiHarian(){
        $todaydate=date("Y-m-d").' 00:00:00';
        $donasi=Donasi::with('donatur','rekeningbank','cicilan')->where('donasi_tanggal',$todaydate)->get();
        foreach ($donasi as $key => $dns) {
            $dns->donasi_durasi=$dns->donasi_total_harga/$dns->donasi_nominal;
        }
        return view('report/donasiharian',compact('donasi'));
    }
    #modul yang menanangani cicilan donatur
    public function reportDonaturDonasiIndex(){
        return view('report/donaturdonasiindex');
    }
    public function reportDonaturDonasiMain(Request $request){
        $donaturid=$request->get('id_entitas');
        return view('report/donaturdonasimain',compact('donaturid'));
    }
    public function reportDonaturDonasiCicilanMain(Request $request){
        $donasiid=$request->get('id_donasi');
        $donasi=Donasi::with('donatur','cicilan')->where('id',$donasiid)->first();
        return view('report/donaturdonasicicilanlist',compact('donasi'));
    }
    public function reportDonaturDonasiCicilanCetak(Request $request){
        if($request->donasi_cicilan_state=='CICILAN'){
            $donasiid=$request->donasi_id;
            $donasi=Donasi::where('id',$donasiid)->first();
            $tanggalakhir=DonasiCicilan::where('donasi_id',$donasiid)->orderBy('cicilan_jatuh_tempo','desc')->first()->cicilan_jatuh_tempo;
            $donasi->donasi_tanggal_akhir=$tanggalakhir;
            $donasino=$donasi->donasi_no;
            $pdf = PDF::loadView('email/pdfcicilan', compact('donasi'))
            ->setPaper([0, 0, 700, 900], 'potrait'); //dalam point unit(bukan mm)
            $filename="cicilan_donasi_".$donasino.'.pdf';
            return $pdf->stream(); //output web
        }else{
            $donasicicilanid=$request->donasi_cicilan_id;
            $donasicicilan=DonasiCicilan::with('donasi.donatur','bayar')->where('id',$donasicicilanid)->first();
            $donasino=$donasicicilan->donasi->donasi_no;
            $cicilanke=$donasicicilan->cicilan_ke;
            $pdf = PDF::loadView('email/pdfinvoice', compact('donasicicilan'))
            ->setPaper([0, 0, 650, 900], 'potrait')->setOptions([
                'tempDir' => public_path(),
                'chroot'  => realpath(base_path()),
            ]); //dalam point unit(bukan mm)
            $filename=$donasino.'-'.$cicilanke.'.pdf';
            return $pdf->stream(); //output web
        }
    }
    #report outstanding cicilan
    public function reportOutStandingCicilan(){
        $todaydate=date("Y-m-d");
        $donasicicilan=DonasiCicilan::with('donasi.donatur','bayar')->where([['cicilan_jatuh_tempo',$todaydate],['cicilan_status','1']])
            ->get(); 
            return view('report/donasicicilanoutstanding',compact('donasicicilan'));
    }

    public function reportSantriBimbingan(){
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

        return view('report/bimbinganlist',compact('bimbingan'));
    }

    public function reportSantriBaru(){
        $santri=Santri::whereIn('santri_status',['1','2','3'])->get();
        foreach ($santri as $key => $snt) {
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
        return view('report/santribarulist',compact('santri'));
    }

    

}
