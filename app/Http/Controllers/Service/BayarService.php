<?php

namespace App\Http\Controllers\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bayar;
use App\Models\Donasi;
use App\Models\DonasiCicilan;
use App\Models\Donatur;
use App\Models\User;
use App\Http\Controllers\Service\MessageService;

class BayarService extends Controller
{
    public function bayarPeriksaHarian(Request $request){
        #periksan cicilan yang sesuai dengan tanggal aktif
        #jika sesuai lakukan pemeriksaan kedalam rekening
        #jika ditemukan update status pembayaran cicilan
    }

    public function bayarCicilan($cicilanid,$tglbayar){
        #ambil donasi id dari tabel cicilan
        $donasicicilan=DonasiCicilan::where('id',$cicilanid)->first();
        $donasiid=$donasicicilan->donasi_id;
        $cicilanke=$donasicicilan->cicilan_ke;

        #update bayar menjadi sudah terbayar
        Bayar::where('cicilan_id',$cicilanid)->update(['bayar_tanggal'=>$tglbayar,'bayar_status'=>'2']);

        #update status donasi, jika cara bayar 4 berarti pembayaran langsung pelunasan
        $donasi=Donasi::with('cicilan')->where('id',$donasiid)->first();
        if($donasi->donasi_cara_bayar=='4'){ //pembayaran tunai
            Donasi::where('id',$donasiid)->update(['donasi_status'=>'3']);
        }else{
            #jika status selainya di hitung, apakah cicilan yang lunas sudah mencapai lebih dari 200rb
            #jika sudah update status donasi menajadi elogile for random (2)
            $cicilan=$donasi->cicilan;
            $totalbayar=0;
            foreach ($cicilan as $key => $cicil) {
                if($cicil->cicilan_status=='2'){
                    $totalbayar=$totalbayar+$cicil->cicilan_nominal;
                }
            }
            if($totalbayar>=200000){
                //update status donasi menjadi bisa random santri
                Donasi::where('id',$donasiid)->update(['donasi_status'=>'2']);
            }
        }

        $donaturid=$donasi->donatur_id;
        #kirikan pesan
        $msg=new MessageService;
        $pengirim='0'; //dari sistem
        
        $emaildonatur=Donatur::where('id',$donaturid)->first()->donatur_email;
        $tujuan=User::where('email',$emaildonatur)->first()->id;
        $isi='Terimakasih, Pembayaran cicilan ke-'.$cicilanke.' telah diterima';
        $msg->saveNotification($pengirim,$tujuan,$isi);
    }
}
