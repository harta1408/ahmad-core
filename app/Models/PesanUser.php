<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesanUser extends Pivot
{
    #tabel berisi pesan dan user, di simpan pada saat membuat pesa
    #ada status pesan terbaca atau belum
    protected $table='pesan_user';
    protected $fillable=[
        'pesan_id', //id pesan
        'user_id',  //id user
        'pesan_terbaca', //status pesan terbaca
    ];
}

