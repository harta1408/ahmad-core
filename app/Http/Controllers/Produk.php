<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Produk;

class Produk extends Controller
{
    public function getProdukList(){
        $produk=Produk::where('produk_status','1')->get();
        return response()->json($produk,200);
    }
    public function getProdukById($id){
        $produk=Produk::where('id',$id)->first();
        return $produk;
    }
    public function saveProduk(Request $request){
        $produk=new Produk;
        $produk->produk_nama=$request->get('produk_nama');
        $produk->produk_desk=$request->get('produk_desk');
        $produk->produk_photo=$request->get('produk_photo');
        $produk->produk_harga=$request->get('produk_harga');
        $produk->produk_status=$request->get('produk_status');
        $produk->save();
    }
}
