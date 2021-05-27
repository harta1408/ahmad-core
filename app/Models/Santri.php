<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    #tabel berisi informasi santri
    protected $table='santri';
    protected $fillable=[
        'santri_kode', //kode santri
        'santri_id', //ktp, sim, kta 
        'santri_email', //alamat email
        'santri_nama', //nama santri
        'santri_tmp_lahir', //tempat lahir
        'santri_tgl_lahir',  //tanggal lahir
        'santri_gender', //PRIA atau WANITA
        'santri_telepon', //nomor handphone
        'santri_kerja', //pekerjaan santri
        'santri_lokasi_photo',//lokasi photo di server
        'santri_alamat', //alaman santri
        'santri_kode_pos', //kode pos
        'santri_kelurahan', //keluarahan
        'santri_kecamatan', //kecamatan
        'santri_kota', //kota
        'santri_provinsi',// propinsi
        'santri_rangkap', // untuk memeriksa apakah santri merangkap entitas lain (donatur/pendamping)
        'santri_status', //0=tidak aktif 1=aktif 2=sudah dapat produk 3=dalam bimbingan
    ];

    public function donatur(){
        return $this->belongsToMany('App\Models\Donatur','donatur_santri','santri_id','donatur_id','id','id')
                    ->as('santridonatur')
                    ->withPivot('donatur_santri_status')
                    ->withTimestamps();
    }
    public function kuesioner(){
        return $this->belongsToMany('App\Models\Kuesioner','kuesioner_santri','santri_id','kuesioner_id','id','id')
                    ->as('santrikuesioner')
                    ->withPivot('kuesioner_jawab','kuesioner_nilai')
                    ->withTimestamps();
    }
}
