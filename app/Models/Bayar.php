<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bayar extends Model
{
    protected $table='bayar';
    protected $fillable=[
        'bayar_total',
        'bayar_kode_unik',
        'bayar_termin', 
        'bayar_status',  
    ];
  
}


