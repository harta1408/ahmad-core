<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DonaturSantri extends Pivot
{
    #tabel yang menyimpan relasi antara donatur dan santri, setiap donatur dapat 
    #memiliki beberapa santri
    #santri bisa saja mendapatkan donatur lain untuk produk berbeda
    protected $table='donatur_santri';
    protected $fillable=[
        'donatur_id', // id donatur
        'santri_id',  //id santri
        'donasi_id', //id donasi
        'donatur_santri_status', //status donatur dan santri
    ];
}

