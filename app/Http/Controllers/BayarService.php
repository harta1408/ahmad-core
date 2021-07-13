<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Bayar;
use App\Models\Donasi;
use App\Models\DonasiCicilan;
use App\Models\Donatur;
use App\Models\User;
use App\Http\Controllers\MessageService;

class BayarService extends Controller
{
    public function bayarPeriksaHarian(Request $request){
        #periksan cicilan yang sesuai dengan tanggal aktif
        #jika sesuai lakukan pemeriksaan kedalam rekening
        #jika ditemukan update status pembayaran cicilan
    }

    public function bayarCicilanPertama($donasiid,$tglbayar){
        #update cicilan status menjadi sudah terbayar
        $cicilanid=DonasiCicilan::where([['donasi_id',$donasiid],['cicilan_ke','1']])->first()->id;
        DonasiCicilan::where('id',$cicilanid)->update(['cicilan_status'=>'2']);

        #update bayar menjadi sudah terbayar
        Bayar::where('cicilan_id',$cicilanid)->update(['bayar_tanggal'=>$tglbayar,'bayar_status'=>'2']);

        $donaturid=Donasi::where('id',$donasiid)->first()->donatur_id;
        #kirikan pesan
        $msg=new MessageService;
        $pengirim='0'; //dari sistem
        
        $emaildonatur=Donatur::where('id',$donaturid)->first()->donatur_email;
        $tujuan=User::where('email',$emaildonatur)->first()->id;
        $isi='Terimakasih, Pembayaran cicilan pertama telah diterima';
        $msg->saveNotification($pengirim,$tujuan,$isi);
    }
}
