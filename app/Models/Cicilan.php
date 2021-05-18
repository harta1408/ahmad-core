<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cicilan extends Model
{
    protected $table='cicilan';
    protected $fillable=[
        'donatur_id',
        'bayar_id',
        'cicilan_ke',
        'cicilan_jumlah', 
        'cicilan_status',  
    ];
    public function donatur(){
        return $this->hasOne('App\Models\Donatur','id','donatur_id');
    }  
    public function bayar(){
        return $this->hasOne('App\Models\Bayar','id','bayar_id');
    }  
}
