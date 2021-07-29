<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Models\KirimProduk;
use App\Models\KirimManifest;
use App\Models\KodePos;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Config;
class KirimProdukService extends Controller
{
    public function hitungbiayakirim($parameter){
        $url = Config::get('ahmad.rajaongkir.url.cost');
    	$key = Config::get('ahmad.rajaongkir.key');

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $parameter,
          CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key:".$key
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            return response()->json(['STATUS' => 'ER', 'MSG' => 'Error']); 
        }

        $result=json_decode($response);

        if($result->rajaongkir->status->code=="200"){
            $biaya=$result->rajaongkir->results;
            return $biaya;
        }
        return response()->json(['STATUS' => 'ER', 'MSG' => 'Error']);        
    }

    public function lacakPengirimanHarian(){
        $kirimproduk=KirimProduk::where('kirim_status','1')->get();
        foreach ($kirimproduk as $key => $kp) {
            $kirimid=$kp->id;
            $noresi=$kp->kirim_no_resi;
            $kuririd=$kp->kirim_kurir_id;
            if(!$noresi){
                continue; //jika belum ada nomor resi di lewat
            }
            $result=$this->lacakPengiriman($noresi,$kuririd);
            if($result=='ERROR'){
                echo('ERROR KONEKSI');
                continue;
            }else{
                $this->updateKirimManifest($kirimid,$result);
            }
        }
    }
    private function updateKirimManifest($kirimid,$result){
        $manifest=$result->rajaongkir->result->manifest;
        $delivered=$result->rajaongkir->result->delivered;
        $noresi=$result->rajaongkir->query->waybill;
        $kuririd=$result->rajaongkir->query->courier;

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
        }
    }

    public function lacakPengiriman($noresi,$kuririd){
        $url = Config::get('ahmad.rajaongkir.url.waybill');
    	$key = Config::get('ahmad.rajaongkir.key');
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "waybill=".$noresi."&courier=".$kuririd,
        // CURLOPT_POSTFIELDS => "waybill=540030016768320&courier=jne",
        // CURLOPT_POSTFIELDS => "waybill=10001812577713&courier=anteraja",
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key:".$key
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'ERROR'; 
        }

        $result=json_decode($response);

        return $result;
    }

    public function mapingPropinsi(){
        $url = Config::get('ahmad.rajaongkir.url.province');
    	$key = Config::get('ahmad.rajaongkir.key');
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'key' => $key,
            ]
        ]);
		try {
            $response = $client->get($url,
                [
                    'verify' => true
                ]
            );
            $result = json_decode($response->getBody()->getContents());
           
        } catch (\Exception $e) {
            return response()->json(['STATUS' => 'ER', 'MSG' => $e->getMessage()]);
        }
        if(!$result){
            return response()->json(['STATUS' => 'UN', 'KET' => 'Tidak Mendapat Respon dari Server']);
        }
        if($result->rajaongkir->status->code=="200"){
            $result=$result->rajaongkir->results;
        }


        foreach ($result as $key => $provinsi) {
            $namapropinsi=$provinsi->province;
            $idpropinsi=$provinsi->province_id;
            $this->updateProvinsiId($namapropinsi,$idpropinsi);
        }
        return response()->json($result); 
    }
    public function mapingKota(){
        $url = Config::get('ahmad.rajaongkir.url.city');
    	$key = Config::get('ahmad.rajaongkir.key');
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'key' => $key,
            ]
        ]);
		try {
            $response = $client->get($url.'?city=39',
                [
                    'verify' => true
                ]
            );
            $result = json_decode($response->getBody()->getContents());
           
        } catch (\Exception $e) {
            return response()->json(['STATUS' => 'ER', 'MSG' => $e->getMessage()]);
        }
        if(!$result){
            return response()->json(['STATUS' => 'UN', 'KET' => 'Tidak Mendapat Respon dari Server']);
        }
        if($result->rajaongkir->status->code=="200"){
            $result=$result->rajaongkir->results;
        }


        foreach ($result as $key => $kota) {
            $idpropinsi=$kota->province_id;
            $idkota=$kota->city_id;
            $namakota=$kota->city_name;
            // $this->updateProvinsiId($namapropinsi,$idpropinsi);
            echo($idkota.$namakota."\n");
        }
        return response()->json($result); 
    }
    private function updateProvinsiId($namapropinsi,$idpropinsi){
        KodePos::where('provinsi',$namapropinsi)->update(['provinsi_id'=>$idpropinsi]);
    }
    private function updateKotaId($idpropinsi){
        KodePos::where('provinsi_id',$idpropinsi)->update(['provinsi_id'=>$idpropinsi]);
    }
}
