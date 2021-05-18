<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukDetail extends Model
{
    protected $table='produk_detail';
    protected $fillable=[
        'produk_id',
        'produk_detail_nama',
        'produk_detail_jml',
        'produk_detail_harga', 
        'produk_detail_status',  
    ];
    public function produk(){
        return $this->hasOne('App\Models\produk','id','produk_id');
    }  
}
 