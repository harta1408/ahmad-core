<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    #tabel yang berisi waktu dimulainya bimbingan, di eksekusi secara otomatis ketika 
    #produk dikirimkan, namun statusnya belum aktif
    #status bimbingan aktif ketika produk sampai ke santri, proses ini bisa di override 
    #apabila sistem cek pengiriman tidak berjalan
    protected $table='bimbingan';
    protected $fillable=[
        'santri_id', //id santri
        'pendamping_id', //id pendamping
        'produk_id', //id produk
        'bimbingan_mulai', //tanggal memulai bimbingan
        'bimbingan_berakhir', //tanggal akhir bimbingan
        'bimbingan_nilai_angka', //nilai angka kelulusan
        'bimbingan_nilai_huruf', //nilai huruf kelulusam
        'bimbingan_predikat', //predikat kelulusan
        'bimbingan_catatan', //catatan pendamping jika ada
        'bimbingan_status', //0=belum aktif 1=aktif 2=selesai

        //dummy
        'bimbingan_progress',

    ];
    public function santri()
    {
        return $this->hasOne('App\Models\Santri','id','santri_id');
    }
    public function pendamping()
    {
        return $this->hasOne('App\Models\Pendamping','id','pendamping_id');
    }
    public function produk()
    {
        return $this->hasOne('App\Models\Produk','id','produk_id');
    }
    public function materi(){
        return $this->belongsToMany('App\Models\Materi','bimbingan_materi','bimbingan_id','materi_id','id','id')
                    ->as('bimbinganmateri')
                    ->withPivot('bimbingan_materi_angka','bimbingan_materi_huruf','bimbingan_materi_catatan')
                    ->withTimestamps();
    }
}


 