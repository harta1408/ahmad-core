<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BimbinganMateri extends Pivot
{
    #tabel relasi hubungan bimbingan dengan materi
    protected $table='bimbingan_materi';
    protected $fillable=[
        'bimbingan_id', //id bimbingan
        'materi_id', //id materi
        'bimbingan_materi_angka', //nilai bimbingan untuk materi tsb berupa angka
        'bimbingan_materi_huruf', //nilai bimbingan untuk materi tsb berupa huruf
        'bimbingan_materi_catatan', //catatan pendamping jika ada
    ];
}

