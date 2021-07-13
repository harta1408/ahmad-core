<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PendampingHadiah extends Pivot
{
    #relasi pendamping dan hadiah, setiap pendamping bisa mendapatkan lebih
    #dari satu hadiah, dengan berbeda periode pemberian hadiah
    protected $table='pendamping_hadiah';
    protected $fillable=[
        'pendamping_id', //id pendamping
        'hadiah_id',  //id hadiah
        'pendamping_hadiah_nilai', //nilai hadiah yang diterima
        'pendamping_hadiah_status', //status pendamping hadiah
    ];
}

