<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesanUser extends Pivot
{
    #berisi data user yang dikirimi pesan dari lembaga ke nomor masing masing
    protected $table='pesan_user';
    protected $fillable=[
        'pesan_id', //id pesan
        'user_id',  //id user
    ];
}

