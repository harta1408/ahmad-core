<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonasiDonatur extends Pivot
{
    #tabel yang menunjukan donasi yang dipilih oleh donatur
    #berfungsi memnyimpan data donasi, termasuk apabila ada
    #cicilan
    protected $table='donasi_donatur';
    protected $fillable=[
        'donatur_id', //id donatur
        'donasi_id',  //id donasi
        'cicilan_ke',  //cicilan ke
        'cicilan_jumlah', //cicilan jumlah
        'cicilan_status', //cicilan status
    ];
}
