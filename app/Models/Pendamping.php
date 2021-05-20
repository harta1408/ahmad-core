<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendamping extends Model
{
    protected $table='pendamping';
    protected $fillable=[
        'pendamping_email',
        'pendamping_kode',
        'pendamping_phone',
        'pendamping_nama',
        'pendamping_gender',
        'pendamping_alamat',
        'pendamping_status_pegawai',
        'pendamping_is_active',
        'pendamping_honor',
        'pendamping_komisi', 
        'pendamping_rangkap', // untuk memeriksa apakah santri merangkap entitas lain (donatur/pendamping)
        'pendamping_status',  //0=tidak aktif 1=aktif 2=sudah dapat produk 3=dalam bimbingan
    ];
}


    