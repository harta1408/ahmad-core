<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    #tabel soal, berisi bank soal yang diberikan untuk menguji santri
    protected $table='soal';
    protected $fillable=[
        'materi_id', //id materi
        'soal_deskripsi', //pertanyaan 
        'soal_jenis', //1=pilihan 2=esay
        'soal_pilihan_a', //pilihan a
        'soal_pilihan_b', //pilihan b
        'soal_pilihan_c', //pilihan c
        'soal_pilihan_d', //pilihan d
        'soal_nilai_maksimum', //nilai maksimum
        'soal_nilai_minimum', //nilai minimum
        'soal_status', //0=tidak aktif 1=aktif
    ];
}

