<?php

namespace App\Http\Controllers\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Donasi;
use Config;

class AccountService extends Controller
{
    public function mootaDaftarBank(){
        $url = Config::get('ahmad.moota.url.bank');
    	$key = Config::get('ahmad.moota.personaltoken');

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$key
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $result=json_decode($response);

        return $result;
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
