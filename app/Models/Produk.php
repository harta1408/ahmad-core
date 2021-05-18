<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table='produk';
    protected $fillable=[
        'produk_nama',
        'produk_desk',
        'produk_photo',
        'produk_harga', 
        'produk_status',  
    ];
    public function beli(){
        return $this->belongsToMany('App\Models\Produk','beli_produk','produk_id','beli_id','id','id')
                    ->as('produkbeli')
                    ->withPivot('beli_produk_jml','beli_produk_harga','beli_produk_total')
                    ->withTimestamps();
    }
}
