<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class KuesionerSantri extends Pivot
{
    #berisi relasi antaa kuesioner dan santri, berisi jawaban santri
    #pada saat mengisi kuesioner
    protected $table='kuesioner_santri';
    protected $fillable=[
        'santri_id', //id santri
        'kuesioner_id', //id kuesioner
        'kuesioner_jawab', //jawab santri terhadap kuesioner
        'kuesioner_nilai',  //nilai jawaban santri
    ];
}
