<?php

namespace App\Http\Controllers\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\DonasiCicilan;
use App\Models\Bayar;
use App\Http\Controllers\Service\DonasiService;
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
    public function mootaGetMutasiByBank($bankid){
        $url = Config::get('ahmad.moota.url.mutasi');
    	$key = Config::get('ahmad.moota.personaltoken');
        $datestart="";//date('Y-m-d');
        $dateend="";//date('Y-m-d');
        $mutationtype="CR"; //Credit=dana masuk

        $url=$url."?amount=&description=&note=&date=&page=&type=".$mutationtype."&bank=".$bankid
            ."&start_date=".$datestart."&end_date=".$dateend;
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
    public function mootaCekBayarDonasiByCicilanId($cicilanid,$datestart,$dateend){
        $bayar=Bayar::where([['cicilan_id',$cicilanid],['bayar_status','1']])->first();
        $amount=$bayar->bayar_total;
        $bayarid=$bayar->id;
        $result=$this->mootaFindAmount($amount,$datestart,$dateend);
        if($result->data!=null){
            #update status pembayaran dan kirimkan pesan
            $tglbayar=$datestart.' 00:00:00';
            $donasiserv=new DonasiService;
            $donasiserv->bayarCicilan($cicilanid,$tglbayar);
            // echo($cicilanid."\n");
            return true;
        }
        return false;
    }

    public function mootaCekBayarDonasi(){
        #modul untuk pengecekan otomatis pembayaran dalam periode satu jam, ketika donatur
        #melakukan konfirmasi pembayaran
        $datestart=date('Y-m-d');
        $dateend=date('Y-m-d');
        #ambil data pembayaran yang sudah di generate setiap malam
        #ambil data dari status yang belum bayar, simpan mutation id di database
        #pastikan mutasi id data yang diterima beda dengan hasil pencarian
        $jatuhtempo=date("Y-m-d").' 00:00:00';
        $cicilanjatuhtempo=DonasiCicilan::where([['cicilan_jatuh_tempo',$jatuhtempo],['cicilan_status','1']])->get('id');
        foreach ($cicilanjatuhtempo as $key => $value) {
            $cicilanid=$value->id;
            $bayar=Bayar::where([['cicilan_id',$cicilanid],['bayar_status','1']])->first();
            $amount=$bayar->bayar_total;
            $bayarid=$bayar->id;
            $result=$this->mootaFindAmount($amount,$datestart,$dateend);
            if($result->data!=null){
                #update status pembayaran dan kirimkan pesan
                $tglbayar=date('Y-m-d H:i:s');
                $donasiserv=new DonasiService;
                $donasiserv->bayarCicilan($cicilanid,$tglbayar);
                // echo($cicilanid."\n");
            }
           
        }
        return;
    }
    private function mootaFindAmount($amount,$datestart,$dateend){
        $url = Config::get('ahmad.moota.url.mutasi');
    	$key = Config::get('ahmad.moota.personaltoken');
        $bankid=Config::get('ahmad.moota.url.bankid');

        $mutationtype="CR"; //Credit=dana masuk

        $url=$url."?amount=".$amount."&description=&note=&date=&page=&type=".$mutationtype."&bank=".$bankid
            ."&start_date=".$datestart."&end_date=".$dateend;
        
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
