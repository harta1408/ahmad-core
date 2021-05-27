<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    #tabel untuk menyimpan informasi produk
    protected $table='produk';
    protected $fillable=[
        'produk_nama', //nama produk
        'produk_deskripsi', //penjelasan produk
        'produk_lokasi_gambar', //lokasi gambar produk
        'produk_lokasi_video', //lokasi video produk
        'produk_harga',  //harga produk
        'produk_stok', // jumlah stok
        'produk_status',  //0=aktif 1=non aktif
    ];
    public function beli(){
        return $this->belongsToMany('App\Models\Produk','beli_produk','produk_id','beli_id','id','id')
                    ->as('produkbeli')
                    ->withPivot('beli_produk_jml','beli_produk_harga','beli_produk_total')
                    ->withTimestamps();
    }
}

