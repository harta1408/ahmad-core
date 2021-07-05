<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeritaSantri extends Pivot
{
    #berfungsi untuk mengetahui, apakah berita yang dikirim ke donatur sudah
    #dibaca atau belum
    protected $table='berita_santri';
    protected $fillable=[
        'berita_id', //id berita
        'santri_id', //id santri
        'berita_santri_status',  //0:belum di baca 1:sudah di baca 2:dihapus
    ];
} 