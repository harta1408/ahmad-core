<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KodePosController extends Controller
{
    public function kodeposProvinsiAll(){
        $kodepos=KodePos::groupBy('provinsi')->get('provinsi');
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
}
