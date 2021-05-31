<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class BeritaDonatur extends Model
{
    #berfungsi untuk mengetahui, apakah berita yang dikirim ke donatur sudah
    #dibaca atau belum
    protected $table='berita_donatur';
    protected $fillable=[
        'berita_id', //id berita
        'donatur_id', //id donatur
        'berita_donatur_status',  //0:belum di baca 1:sudah di baca
    ];
}

