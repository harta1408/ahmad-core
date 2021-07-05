<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    protected $fillable=[
        'dash_donasi_nilai',
        'dash_donasi_jumlah',
    ];
}
