<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
    #tabel lembaga menyimpan informasi terkait dengan lembaga, selain itu tabel ini
    #juga berisi konten yang di tampilkan pada halaman utama, konten tidak bisa di
    #tambahkan, sifatnya hanya replace/ mengganti konten yang ada
    protected $table='lembaga';
    protected $fillable=[
        'lembaga_kode',
        'lembaga_email',
        'lembaga_phone',
        'lembaga_nama',
        'lembaga_alamat',
        'lembaga_deskripsi', 
        'lembaga_main_judul', //untuk mengubah judul konten halaman utama
        'lembaga_main_konten', //untuk mengubah konten halaman pertama
        'lembaga_main_lokasi_gambar',
        'lembaga_donatur_judul', //mengubah judul konten donatur halaman pertama
        'lembaga_donatur_konten', //mengubah konten donatur halaman pertama
        'lambaga_santri_judul',
        'lembaha_santri_konten',
        'lambaga_pendamping_judul',
        'lembaha_pendamping_konten',
        'lambaga_produk_judul',
        'lembaha_produk_konten',
        'lambaga_mitra_judul',
        'lembaha_mitra_konten',
        'lembaga_status', //0=tidak aktif 1=aktif 
    ];
}

