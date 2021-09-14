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
        'santri_kecamatan_id', //subdistrict_id
        'santri_kota_id', //city_id
        'santri_provinsi_id',// province_id
        'santri_rangkap', // untuk memeriksa apakah santri merangkap entitas lain (donatur/pendamping)
        'santri_min_referral', //hitungan minimal refferal untuk selalu mengingatkan
        'santri_status', //0=tidak aktif 1=aktif data belum lengkap 2=belum isi kuesioner 3=belum otorisasi, data belum lengkap 
                         //4=aktif data sudah lengkap, menunggu produk  5=terpilih, menunggu produk 6=sudah dapat produk, dalam, dalam bimbingan 7=lulus

        //dummy
        'santri_progress_bimbingan',
    ];

    public function donatur(){
        return $this->belongsToMany('App\Models\Donatur','donatur_santri','santri_id','donatur_id','id','id')
                    ->as('santridonatur')
                    ->withPivot('donasi_id','pendamping_id','donatur_santri_status')
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
    public function kirimproduk(){
        return $this->belongsTo('App\Models\KirimProduk','id','santri_id');
    }
    public function user(){
        return $this->hasOne('App\Models\User','email','santri_email');
    }
    public function pengingat(){
        return $this->belongsToMany('App\Models\Pengingat','pengingat_santri','santri_id','pengingat_id','id','id')
                    ->as('santripengingat')
                    ->withPivot('pengingat_santri_index','pengingat_santri_respon','pengingat_santri_status')
                    ->withTimestamps();
    }
}
