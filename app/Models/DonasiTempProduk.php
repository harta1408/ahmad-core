<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DonasiTempProduk extends Pivot
{
    #tabel yang berisi produk yang di donasikan, hubungan many to many karena ada
    #kemungkinan produk lebih dari satu jenis
    protected $table='donasi_produk_temp';
    protected $fillable=[
        'temp_donasi_no', //id donasi/ nomor donasi
        'produk_id', //id produk
        'temp_donasi_produk_jml', //jumlah produk yang di donasikan
        'temp_donasi_produk_harga', //harga produk yang di donasikan
        'temp_donasi_produk_total', //total harga produk yang di donasikan
    ];

}
