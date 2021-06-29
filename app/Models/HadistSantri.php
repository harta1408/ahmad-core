<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HadistSantri extends Pivot
{
    protected $table='hadist_santri';
    protected $fillable=[
        'hadist_id', //id hadist
        'santri_id', //id santri
        'hadist_santri_status',  //0:belum di baca 1:sudah di baca 2:dihapus
    ];
}
