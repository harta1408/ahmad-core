<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KirimProduk extends Model
{
    protected $table='kirim_produk';
    protected $fillable=[
        'produk_id', //kode produk
        'kirim_nama', //
        'kirim_telepon',
        'kirim_penerima_nama',
        'kirim_penerima_telepon',
        'kirim_penerima_alamat', //pertanyaan
        'kirim_penerima_kode_pos',
        'kirim_penerima_kota',
        'kirim_penerima_kecamatan', //bobot jawaban YA
        'kirim_penerima_kelurahan', //bobot jawaban TIDAK
        'kirim_penerima_provinsi',
        'kirim_no_resi',
        'kirim_biaya',
        'kirim_status', //kode pos
    ];
}

 
