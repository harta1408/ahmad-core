<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KirimProduk extends Model
{
    #tabel yang berisi informasi pengiriman produk, untuk mempermudah
    #dalam perlacakan produk
    protected $table='kirim_produk';
    protected $fillable=[
        'produk_id', //kode produk
        'santri_id', //kode santri yang mendapatkan produk
        'kirim_produk_no_seri', //nomor seri produk yang dikirimkan
        'kirim_nama', //nama user pengirim 
        'kirim_telepon', //telp pengirim
        'kirim_no_resi', //nomor resi pengiriman
        'kirim_tanggal_kirim', //tanggal pengiriman 
        'kirim_tanggal_terima', //tanggal diterima
        'kirim_biaya', //biaya pengiriman

        //backend, ditampilkan sebagai informasi
        'kirim_penerima_nama', //nama santri penerima
        'kirim_penerima_telepon', //telpon santri penerima
        'kirim_penerima_alamat', //alamat santri penerima
        'kirim_penerima_kode_pos', //kode pos santri penerima
        'kirim_penerima_kota', //kota santri penerima
        'kirim_penerima_kecamatan', //kecamatan santri penerima
        'kirim_penerima_kelurahan', //kelurahan santri penerima
        'kirim_penerima_provinsi', //provinsi santri penerima
        'kirim_status', //kode pos
    ];
}

 
