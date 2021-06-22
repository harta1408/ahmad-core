<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    #tabel soal, berisi bank soal yang diberikan untuk menguji santri
    #satu materi bisa memiliki banyak soal, untuk menghitung nilai santri adalah dengan
    #membagi total nilai maksimum dengan nilai yang di dapat santri
    protected $table='soal';
    protected $fillable=[
        'materi_id', //id materi
        'soal_no', //nomor soal 
        'soal_deskripsi', //pertanyaan 
        'soal_jenis', //1=esay 2=pilihan 
        'soal_pilihan_a', //pilihan a
        'soal_pilihan_b', //pilihan b
        'soal_pilihan_c', //pilihan c
        'soal_pilihan_d', //pilihan d
        'soal_nilai_maksimum', //nilai maksimum
        'soal_nilai_minimum', //nilai minimum
        'soal_status', //0=tidak aktif 1=aktif
    ];
    public function materi(){
        return $this->hasOne('App\Models\Materi','id','materi_id');
    }
}

