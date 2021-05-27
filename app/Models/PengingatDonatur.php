<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengingatDonatur extends Model
{
    #tabel berisi relasi pengingat dan donatur untuk mengetahui respon donatur
    #terhadap pengingat tersebut
    protected $table='pengingat_donatur';
    protected $fillable=[
        'donatur_id', //id donatur
        'pengingat_id', //id pengingat
        'pengingat_donatur_respon', //0=no respon 1=berterimakasih 2=biasa saja 3=tidak suka
    ];
}

