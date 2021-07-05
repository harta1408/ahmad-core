<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    #tabel berisi informasi santri
    protected $table='santri';
    protected $fillable=[
        'santri_kode', //kode santri
        'santri_nid', //ktp, sim, kta 
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
        'santri_min_referral', //hitungan minimal refferal untuk selalu mengingatkan
        'santri_status', //0=tidak aktif 1=aktif data belum lengkap 2=sudah jawab kuisioner, belum otorisasi 3=sudah otorisasi, data belum lengkap 
        //4=aktif data sudah lengkap  5=terpiilih, menunggu produk 6=sudah dapat produk 7=dalam bimbingan 8=sudah selesai
    ];

    public function donatur(){
        return $this->belongsToMany('App\Models\Donatur','donatur_santri','santri_id','donatur_id','id','id')
                    ->as('santridonatur')
                    ->withPivot('donatur_santri_status')
                    ->withTimestamps();
    }
    public function hadist(){
        return $this->belongsToMany('App\Models\Hadist','hadist_santri','santri_id','hadist_id','id','id')
                    ->as('santrihadist')
                    ->withPivot('hadist_santri_status')
                    ->withTimestamps();
    }
    public function berita(){
        return $this->belongsToMany('App\Models\Berita','berita_santri','santri_id','berita_id','id','id')
                    ->as('santriberita')
                    ->withPivot('berita_santri_status')
                    ->withTimestamps();
    }    
    public function kuesioner(){
        return $this->belongsToMany('App\Models\Kuesioner','kuesioner_santri','santri_id','kuesioner_id','id','id')
                    ->as('santrikuesioner')
                    ->withPivot('kuesioner_jawab','kuesioner_nilai')
                    ->withTimestamps();
    }
    public function user(){
        return $this->hasOne('App\Models\User','email','santri_email');
    }
}
