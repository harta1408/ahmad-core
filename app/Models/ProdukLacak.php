<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukLacak extends Model
{
    #tabel untuk menyimpan data pelacakan dari pihak ketiga (raja ongkir)
    protected $table='produk_lacak';
    protected $fillable=[
        'no_resi', //id santri
        'kurir', //id materi
        'tanggal', //nilai santri
        'deskripsi',
    ];
}

