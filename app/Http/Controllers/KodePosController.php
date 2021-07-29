<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\KodePos;
use GuzzleHttp\Client;
use Config;

class KodePosController extends Controller
{
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
            $kodepos=$result->rajaongkir->results;
            return response()->json($kodepos,200);
        }
        return response()->json(['STATUS' => 'ER', 'MSG' => 'Error']);
    }
    public function kodeposKotaByProvinsi($provinsi){
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
            $kodepos=$result->rajaongkir->results;
            return response()->json($kodepos,200);
        }
        return response()->json(['STATUS' => 'ER', 'MSG' => 'Error']);
    }
    public function kecamatanByKota($kota){
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
            $kodepos=$result->rajaongkir->results;
            return response()->json($kodepos,200);
        }
        return response()->json(['STATUS' => 'ER', 'MSG' => 'Error']);
    }
    public function kelurahanByKecamatan($kecamatan){
        $kodepos=KodePos::where('kecamatan',$kecamatan)->groupBy('kelurahan')->get('kelurahan');
        return response()->json($kodepos,200);
    }
    public function kodeposByKelurahan($kelurahan){
        $kodepos=KodePos::where('kelurahan',$kelurahan)->groupBy('kode_pos')->get('kode_pos');
        return response()->json($kodepos,200);
    }
}
