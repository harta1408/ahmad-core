<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    #pembuat : lembaga
    #pendistribusi : lembaga, donatur, santri dan pendamping
    #tabel berita bisa berisi informasi satu arah, muncul di dashboard memberikan inforamaso
    #berita bisa juga berisi tentang kampanye, yaitu informasi untuk meyakinkan calon
    #donatur, santri dan pendamping untuk bergabung dengan program ahmad
    #kampanye bisa muncul di dashboard bisa juga dikirimkan melalui whatsapp
    #berita di buat oleh lembaga ketika akan di share oleh pendamping, dengan memilah yang ada
    protected $table='berita';
    protected $fillable=[
        'berita_isi',
        'berita_jenis', //1=berita 2-kampanye 3=kampanye broadcast wa
        'berita_entitas',
        'berita_lokasi_gambar',
        'berita_lokasi_video',
        'berita_status',
    ];
}
