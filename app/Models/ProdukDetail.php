<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukDetail extends Model
{
    #tabel produk detail
    protected $table='produk_detail';
    protected $fillable=[
        'produk_id', //id produk
        'produk_detail_nama', //nama item
        'produk_detail_jml', //jumlah item dalam produk tsb
        'produk_detail_harga', //harga per item
        'produk_detail_status',  //0=non aktif 1=aktif
    ];
    public function produk(){
        return $this->hasOne('App\Models\produk','id','produk_id');
    }  
} 
