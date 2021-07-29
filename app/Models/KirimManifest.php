<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KirimManifest extends Model
{
    protected $table='kirim_manifest';
    protected $fillable=[
        'kirim_produk_id', 
        'kirim_manifest_code', 
        'kirim_manifest_no_resi', 
        'kirim_manifest_kurir', 
        'kirim_manifest_tanggal',
        'kirim_manifest_waktu',
        'kirim_manifest_deskripsi',
        'kirim_manifest_kota',
    ];
}

