<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
    #tabel lembaga
    protected $table='lembaga';
    protected $fillable=[
        'lembaga_kode',
        'lembaga_email',
        'lembaga_phone',
        'lembaga_nama',
        'lembaga_alamat',
        'lembaga_deskripsi', //0=tidak aktif 1=aktif 2=sudah dapat produk 3=dalam bimbingan
    ];
}

