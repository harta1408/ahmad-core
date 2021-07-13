<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class HadistPendamping extends Pivot
{
    protected $table='hadist_pendamping';
    protected $fillable=[
        'hadist_id', //id hadist
        'pendamping_id', //id pendamping
        'hadist_pendamping_status',  //0:belum di baca 1:sudah di baca 2:dihapus
    ];
}
