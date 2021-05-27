<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriProduk extends Pivot
{
    #tabel untuk menyimpan data yang menunjukan materi dimaksud
    #merupakan produk yang mana
    protected $table='materi_produk';
    protected $fillable=[
        'materi_id', //id materi
        'produk_id', //id produk
    ];
}
