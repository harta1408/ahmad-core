<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuesionerSantri extends Pivot
{
    protected $table='kuesioner_santri';
    protected $fillable=[
        'santri_id',
        'kuesioner_id',
        'kuesioner_jawab',
        'kuesioner_nilai',  
    ];
}
