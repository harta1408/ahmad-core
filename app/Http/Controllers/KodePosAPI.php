<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\KodePos;

class KodePosAPI extends Controller
{
    public function kodeposProvinsi($provinsi){
        $kodepos=KodePos::where('provinsi',$provinsi)->get();
        return response()->json($kodepos,200);
    }
    public function kodeposKota($kota){
        $kodepos=KodePos::where('kota',$kota)->get();
        return response()->json($kodepos,200);
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
    public function kodeposByKeluarahan($kelurahan){
        $kodepos=KodePos::where('kelurahan',$kelurahan)->groupBy('kode_pos')->get('kode_pos');
        // $kodepos=KodePos::where('kelurahan',$kelurahan)->groupBy('kode_pos')->pluck('kode_pos');
        return response()->json($kodepos,200);
    }
}
