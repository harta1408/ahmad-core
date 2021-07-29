<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\KodePos;
use GuzzleHttp\Client;
use Config;
class KodePosAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
	}
    public function kodeposProvinsiAll(){
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

        return response()->json($result,200);
    }
    public function kodeposProvinsi($provinsi){
        $url = Config::get('ahmad.rajaongkir.url.city');
    	$key = Config::get('ahmad.rajaongkir.key');
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'key' => $key,
            ]
        ]);
		try {
            $response = $client->get($url.'?province='.$provinsi,
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

        return response()->json($result,200);
    }
    public function kodeposKota($kota){
        $url = Config::get('ahmad.rajaongkir.url.subdistrict');
    	$key = Config::get('ahmad.rajaongkir.key');
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'key' => $key,
            ]
        ]);
		try {
            $response = $client->get($url.'?city='.$kota,
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

        return response()->json($result,200);
    }
    public function kodeposKecamatan($kecamatan){
        $kodepos=KodePos::where('kecamatan',$kecamatan)->get();
        return response()->json($kodepos,200);
    }
    public function kodeposKelurahan($kelurahan){
        $kodepos=KodePos::where('kelurahan',$kelurahan)->get();
        return response()->json($kodepos,200);
    }
    public function kodeposKodePos($kodepos){
        $kodepos=KodePos::where('kode_pos',$kodepos)->get();
        return response()->json($kodepos,200);
    }
    public function kotaByProvinsi($provinsi){
        $kodepos=KodePos::where('provinsi',$provinsi)->groupBy('kota')->get('kota');
        return response()->json($kodepos,200);
    }
    public function kecamatanByKota($kota){
        $kodepos=KodePos::where('kota',$kota)->groupBy('kecamatan')->get('kecamatan');
        return response()->json($kodepos,200);
    }
    public function kelurahanByKecamatan($kecamatan){
        $kodepos=KodePos::where('kecamatan',$kecamatan)->groupBy('kelurahan')->get('kelurahan');
        return response()->json($kodepos,200);
    }
    public function kodeposByKelurahan($kelurahan){
        $kodepos=KodePos::where('kelurahan',$kelurahan)->groupBy('kode_pos')->get('kode_pos');
        // $kodepos=KodePos::where('kelurahan',$kelurahan)->groupBy('kode_pos')->pluck('kode_pos');
        return response()->json($kodepos,200);
    }

    public function getProvisiById($idpropinsi){
        $url = Config::get('ahmad.rajaongkir.url.province');
    	$key = Config::get('ahmad.rajaongkir.key');
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'key' => $key,
            ]
        ]);
		try {
            $response = $client->get($url.'?id='.$idpropinsi,
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
            $provinsi=$result->rajaongkir->results->province;
            return $provinsi;
        }
        return response()->json(['STATUS' => 'ER', 'MSG' => 'Error']);
    }
    public function getKotaById($idkota){
        $url = Config::get('ahmad.rajaongkir.url.city');
    	$key = Config::get('ahmad.rajaongkir.key');
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'key' => $key,
            ]
        ]);
		try {
            $response = $client->get($url.'?id='.$idkota,
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
            $kota=$result->rajaongkir->results->city_name;
            return $kota;
        }
        return response()->json(['STATUS' => 'ER', 'MSG' => 'Error']);
    }
    public function getKecamatanById($idkecamatan){
        $url = Config::get('ahmad.rajaongkir.url.subdistrict');
    	$key = Config::get('ahmad.rajaongkir.key');
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'key' => $key,
            ]
        ]);
		try {
            $response = $client->get($url.'?id='.$idkecamatan,
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
            $kecamatan=$result->rajaongkir->results->subdistrict_name;
            return $kecamatan;
        }
        return response()->json(['STATUS' => 'ER', 'MSG' => 'Error']);
    }
    public function getKodePosByKotaId($idkota){
        $url = Config::get('ahmad.rajaongkir.url.city');
    	$key = Config::get('ahmad.rajaongkir.key');
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'key' => $key,
            ]
        ]);
		try {
            $response = $client->get($url.'?id='.$idkota,
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
            $kodepos=$result->rajaongkir->results->postal_code;
            return $kodepos;
        }
        return response()->json(['STATUS' => 'ER', 'MSG' => 'Error']);
    }
}
