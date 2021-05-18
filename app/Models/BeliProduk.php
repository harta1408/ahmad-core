<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeliProduk extends Pivot
{
    protected $table='beli_produk';
    protected $fillable=[
        'beli_id',
        'produk_id',
        'beli_produk_jml',
        'beli_produk_harga', 
        'beli_produk_total',  
    ];}


