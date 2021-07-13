<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hadiah extends Model
{
    protected $table='hadiah';
    protected $fillable=[
        'hadiah_nama', //judul hadist
        'hadiah_no_seri', //isi hadist
        'hadiah_nilai', //1=hadist 2-kampanye 3=kampanye broadcast wa
        'hadiah_mulai', //0=tidak aktif 1=setiap hari 2=mingguan 3=bulanan 4=waktu tertenu
        'hadiah_akhir', //kirim waktu tertentu status 4
        'hadiah_status', //0=hapus 1=aktif 
    ];
}

