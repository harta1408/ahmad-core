<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Donasi;

class AccountService extends Controller
{
    public function mootaDaftarBank(){

    }
    public function mootaCekMutasiTerakhir(){
        #logic sementara sebelum pakai moota, konsepnya ubah status bayar
        #nantinya cek ke rekening sebelum ubah stats
        $donasi=Donasi::where('donasi_status','1')->get();
        foreach ($donasi as $key => $don) {
            $donasiid=$don->id;
            $this->changeBayarStatus($donasiid);
        }

    }
    private function changeBayarStatus($donasiid){
        #modul untuk mengubah status pada tabel donasi menjadi telah terbayar
        #di eksekusi pada saat mendapat konfirmasi pembayaran dari moota
        Donasi::where('id',$donasiid)->update(['donasi_status'=>'2']);
        return true;
    }
}
