<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodePos extends Model
{
    protected $table='kodepos';
    protected $fillable=[
        'provinsi', //provinsi
        'kota', //kota
        'kecamatan', //kecamatan
        'kelurahan', //keluarahan
        'kode_pos', //kode pos
    ];
}
