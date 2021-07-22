<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
class PengingatDonatur extends Pivot
{
    #tabel berisi relasi pengingat dan donatur untuk mengetahui respon donatur
    #terhadap pengingat tersebut, akan di isi untuk jenis pengingat
    #1, 2 dan 3 saja
    protected $table='pengingat_donatur';
    protected $fillable=[
        'donatur_id', //id donatur
        'pengingat_id', //id pengingat
        'pengingat_donatur_respon', //0=no respon 1=berterimakasih 2=biasa saja 3=tidak suka
        'pengingat_donatur_status', //tanggal aktif
    ];
}

