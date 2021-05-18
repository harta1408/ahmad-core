<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonaturSantri extends Pivot
{
    protected $table='donatur_santri';
    protected $fillable=[
        'donatur_id', 
        'santri_id', 
        'donatur_santri_status', 
    ];
}

