<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendampingDonatur extends Pivot
{
    #tabel relasi hubungan antara pendamping dan donatur, yang terdaftar di sini adalah
    #donatur yang mengikuti proses referral, disimpan hanya pada saat donatur bertransaksi
    #donasi dan mengikuti proses pembayaran
    protected $table='pendamping_donatur';
    protected $fillable=[
        'pendamping_id', //id pedamping
        'donatur_id', //id donatur
    ];
}