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
        'produk_masa_bimbingan', //masa bimbingan dalam hari
        'produk_harga',  //harga produk
        'produk_discount', //discount
        'produk_stok', // jumlah stok
        'produk_status',  //0=aktif 1=non aktif
    ];
    public function donasi(){
        return $this->belongsToMany('App\Models\Produk','donasi_produk','produk_id','donasi_id','id','id')
                    ->as('donasiproduk')
                    ->withPivot('donasi_produk_jml','donasi_produk_harga','donasi_produk_total')
                    ->withTimestamps();
    }
    public function donasitemp(){
        return $this->belongsToMany('App\Models\DonasiTemp','donasi_temp_produk','produk_id','temp_donasi_no','id','temp_donasi_no')
                    ->as('donasiproduktemp')
                    ->withPivot('temp_donasi_produk_jml','temp_donasi_produk_harga','temp_donasi_produk_total')
                    ->withTimestamps();
    }
}

