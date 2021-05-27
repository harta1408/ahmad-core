<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuesionerPendamping extends Pivot
{
    #tabelyang berisi relasi kuesioner dan pendamping, yang menyimpan jawaban
    #pendamping ketika mengisi kuesioner
    protected $table='kuesioner_pendamping';
    protected $fillable=[
        'pendamping_id', //id pendamping
        'kuesioner_id', //id kuesioner
        'kuesioner_jawab', //jawaban pendamping untuk kuisioner
        'kuesioner_nilai', //nilai kuesioner
    ];
}

