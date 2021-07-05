<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeritaPendamping extends Pivot
{
    #berfungsi untuk mengetahui, apakah berita yang dikirim ke donatur sudah
    #dibaca atau belum
    protected $table='berita_pendamping';
    protected $fillable=[
        'berita_id', //id berita
        'pendamping_id', //id pendamping
        'berita_pendamping_status',  //0:belum di baca 1:sudah di baca 2:dihapus
    ];
}
