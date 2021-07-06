<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Produk;
use Validator;

class ProdukAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
	}
    public function produkById($id){
        $produk=Produk::where('id',$id)->first();
        return $produk;
    }            
    #menyimpan data produk beserta detail isinya
    public function produkSimpan(Request $request){
        $validator = Validator::make($request->all(), [
            'produk_nama' => ['required','string','max:30'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $produk=new Produk;
        $produk->produk_nama=$request->get('produk_nama');
        $produk->produk_deskripsi=$request->get('produk_deskripsi');
        $produk->produk_lokasi_gambar=$request->get('produk_lokasi_gambar');
        $produk->produk_lokasi_video=$request->get('produk_lokasi_video');
        $produk->produk_harga=$request->get('produk_harga');
        $produk->produk_stok=$request->get('produk_stok');
        $produk->produk_status='1'; //aktif
        $produk->save();

        return response()->json($produk,200);
    }

}
