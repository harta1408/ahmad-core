<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beli extends Model
{
    protected $table='beli';
    protected $fillable=[
        'donatur_id',
        'beli_tanggal',
        'beli_catatan',
        'beli_total_harga', 
        'beli_total_disc', 
        'beli_total_pajak', 
        'beli_status',
    ];
    public function donatur(){
        return $this->hasOne('App\Models\AgeGroups','id','age_group_id');
    }  
    public function produk(){
        return $this->belongsToMany('App\Models\Produk','beli_produk','beli_id','produk_id','id','id')
                    ->as('beliproduk')
                    ->withPivot('beli_produk_jml','beli_produk_harga','beli_produk_total')
                    ->withTimestamps();
    }
}

