<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Models\DonasiTemp;
use App\Models\Produk;
use Validator;
class DonasiAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
	}
    public function donasiTempSimpan(Request $request){
        $tempdonasino=rand(100000,999999);
        $donasitemp=new DonasiTemp;
        $donasitemp->temp_donasi_no=$tempdonasino;
        $donasitemp->rekening_id=$request->get('rekening_id');
        $donasitemp->temp_donasi_tanggal=$request->get('donasi_tanggal');  
        $donasitemp->temp_donasi_jumlah_santri=$request->get('donasi_jumlah_santri');
        $donasitemp->temp_donasi_tagih=$request->get('temp_donasi_tagih'); 
        $donasitemp->temp_donasi_total_harga=$request->get('donasi_total_harga');
        $donasitemp->temp_donasi_cara_bayar=$request->get('donasi_cara_bayar'); 
        $donasitemp->save();

        // $donasino=DonasiTemp::where('temp_donasi_no',$tempdonasino)->first()->id;
        for ($i=0; $i < count($request->input('donasiproduk')) ; $i++) {
            $produkid=$request->input('donasiproduk')[$i]['produk_id'];
            $produk=Produk::where('id',$produkid)->first(); 
            $donasitemp->produk()->attach(['produk_id'=>$produkid],
                [
                    'temp_donasi_no'=>$tempdonasino,
                    'temp_donasi_produk_jml' => $request->input('donasiproduk')[$i]['temp_donasi_produk_jml'] ,
                    'temp_donasi_produk_harga' =>$request->input('donasiproduk')[$i]['temp_donasi_produk_harga'],
                    'temp_donasi_produk_total' => $request->input('donasiproduk')[$i]['temp_donasi_produk_total'],
                ]);
        }
        $donasi=DonasiTemp::with('produk')->where('temp_donasi_no',$tempdonasino)->first();
        return response()->json($donasi,200);
    }

    public function donasiSimpan(Request $request){
        // $validator = Validator::make($request->all(), [
        //     'donatur_id' => 'required|string',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        // }
        $donasino=$this->donasino();
        $donasi=new Donasi();
        $donasi->donasi_no=$donasino;
        $donasi->donatur_id=$request->get('donatur_id');
        $donasi->donasi_tanggal=$request->get('donasi_tanggal');  
        $donasi->donasi_catatan=$request->get('donasi_catatan');
        $donasi->donasi_jumlah_santri=$request->get('donasi_jumlah_santri');
        $donasi->donasi_total_harga=$request->get('donasi_total_harga');
        $donasi->donasi_cara_bayar=$request->get('donasi_cara_bayar'); //cara pembayaran 1=harian, 2=mingguan, 3=bulanan 4=tunai
        $donasi->donasi_status='1'; //donasi disimpan, belum di bayar
        $donasi->save();

        $donasiid=Donasi::where('donasi_no',$donasino)->first()->id;
        for ($i=0; $i < count($request->input('donasiproduk')) ; $i++) {
            $produkid=$request->input('donasiproduk')[$i]['produk_id'];
            $produk=Produk::where('id',$produkid)->first(); 
            $donasi->produk()->attach(['produk_id'=>$produkid],
                [
                    'donasi_id'=>$donasiid,
                    'donasi_produk_jml' => $request->input('donasiproduk')[$i]['donasi_produk_jml'] ,
                    'donasi_produk_harga' =>$request->input('donasiproduk')[$i]['donasi_produk_harga'],
                    'donasi_produk_total' => $request->input('donasiproduk')[$i]['donasi_produk_total'],
                ]);
        }
        $donasi=Donasi::with('produk')->where('donasi_no',$donasino)->first();
        return response()->json($donasi,200);
    }
    public function donasiBayar(){

    }
    public function donasiPengingat(){

    }
    
    public function donasino()
    {
      //otomatis pengaturan nomor donasi dengan format 
      //tahun[2]+bulan[2]+nomor urut[6]
      $bulan=date("m");
      $tahun=date("y");
      $strNewId = $tahun.$bulan."000001";
      while ($this->findDonasiKode($strNewId)) { 
        $intNewId=substr($strNewId,-6)+1; 
        switch (strlen($intNewId)) {
            case 1:
                $strNewId=$tahun.$bulan.'00000'.$intNewId;
                break;
            case 2:
                $strNewId=$tahun.$bulan.'0000'.$intNewId;
                break;
            case 3:
                $strNewId=$tahun.$bulan.'000'.$intNewId;
                break;
            case 4:
                $strNewId=$tahun.$bulan.'00'.$intNewId;
                break;  
            case 5:
                $strNewId=$tahun.$bulan.'0'.$intNewId;
                break;
            case 6:
                $strNewId=$tahun.$bulan.$intNewId;
                break;  
        }
      }
      return $strNewId;
    }
    private function findDonasiKode($donasino){
        $donasi=Donasi::where('donasi_no',$donasino)->first();
        if($donasi){
          return true;
        }
        return false;
    }
}
