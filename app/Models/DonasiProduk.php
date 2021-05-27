<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonasiProduk extends Model
{
    #tabel yang berisi produk yang di donasikan, hubungan many to many karena ada
    #kemungkinan produk lebih dari satu jenis
    protected $table='donasi_produk';
    protected $fillable=[
        'donasi_id', //id donasi/ nomor donasi
        'produk_id', //id produk
        'donasi_produk_jml', //jumlah produk yang di donasikan
        'donasi_produk_harga', //harga produk yang di donasikan
        'donasi_produk_total', //total harga produk yang di donasikan
    ];
}

