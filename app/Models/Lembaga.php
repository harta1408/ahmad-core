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
        'lembaga_id', 
        'lembaga_nama', 
        'lembaga_email', 
        'lembaga_telepon', 
        'lembaga_alamat', 
        'lembaga_tentang_ahmad_judul', 
        'lembaga_tentang_ahmad_isi',
        'lembaga_landing_donatur_judul', 
        'lembaga_landing_donatur_isi', 
        'lembaga_landing_santri_judul',
        'lembaga_landing_santri_isi', 
        'lembaga_landing_pendamping_judul', 
        'lembaga_landing_pendamping_isi', 
        'lembaga_landing_mitra_judul', 
        'lembaga_landing_mitra_isi', 
        'lembaga_landing_produk_judul', 
        'lembaga_landing_produk_isi',
    ];
    public function faq()
    {
        return $this->hasMany('App\Models\Faq','lembaga_id','lembaga_id');
    }
}