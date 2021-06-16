<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    #tabel yang berisi informasi terkait materi yang diberikan kepada santri
    #jumlah progress materi di hitung dari total jumlah bobot materi yang diberikan
    #di bagi dengan bobot materi yang telah di capai
    protected $table='materi';
    protected $fillable=[
        'materi_nama', //nama materi
        'materi_deskripsi', //penjelasan singkat terkait materi
        'materi_lokasi_gambar', //lokasi gambar materi
        'materi_lokasi_video', //lokasi video mater
        'materi_level', //tingkatan/ bab materi
        'materi_bobot', //bobot nilai materi
        'materi_status', //0=tidak aktif 1=aktif 
    ];
}
