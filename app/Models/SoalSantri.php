<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalSantri extends Pivot
{
    #tabel soal santri, berisi jawaban dari masing masing santri
    protected $table='soal_santri';
    protected $fillable=[
        'soal_id', //idsoal 
        'santri_id', //id santri
        'soal_jawaban', //jawaban
        'soal_nilai',//nilai
    ];
}


