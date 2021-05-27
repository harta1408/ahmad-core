<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    #pesan yang dikirimkan ke seluruh user/pengguna 
    protected $table='pesan';
    protected $fillable=[
        'pembuat_id', //user pembuat pesan
        'pesan_jenis', //1=donatur 2=santri 3=pendamping 
        'pesan_isi',//isi pesan
        'pesan_waktu_kirim', //waktu pengiriman
        'pesan_status', //0=not aktif 1=aktif
    ];
}


 