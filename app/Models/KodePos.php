<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodePos extends Model
{
    protected $table='kodepos';
    protected $fillable=[
        'provinsi_id',
        'provinsi', //provinsi
        'kota_id',
        'kota', //kota
        'kecamatan_id',
        'kecamatan', //kecamatan
        'kelurahan', //keluarahan
        'kode_pos', //kode pos
    ];
}
