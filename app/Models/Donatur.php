<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donatur extends Model
{
    #tabel berisi informasi donatur
    protected $table='donatur';
    protected $fillable=[
        'donatur_kode',
        'donatur_nid', //nomor ktp, sim. kta
        'donatur_email', //email sebagai relasi ke user
        'donatur_nama', //nama donatur
        'donatur_tmp_lahir',
        'donatur_tgl_lahir',
        'donatur_gender', //jenis kelamin PRIA & WANITA
        'donatur_agama', // tertulis ISLAM dst
        'donatur_telepon', //nomor handphone
        'donatur_lokasi_photo',  //lokasi photo donatur di server
        'donatur_kerja', //pekerjaan
        'donatur_alamat', //alamat
        'donatur_kode_pos', //kode pos
        'donatur_kelurahan',// kelurahan
        'donatur_kecamatan', //kecmatan
        'donatur_kota', //kota/kabupaten
        'donatur_provinsi', //provinsi
        'donatur_rangkap', //status merangkap, santri donatur
        'donatur_min_referral', //hitungan minimal refferal untuk selalu mengingatkan
        'donatur_status', //0=tidak aktif 1=aktif data belum lengkap 2=aktif data sudah lengkap 
    ];
    public function santri(){
        return $this->belongsToMany('App\Models\Santri','donatur_santri','donatur_id','santri_id','id','id')
                    ->as('donatursantri')
                    ->withPivot('donasi_id','pendamping_id','donatur_santri_status')
                    ->withTimestamps();
    }
    public function hadist(){
        return $this->belongsToMany('App\Models\Hadist','hadist_donatur','donatur_id','hadist_id','id','id')
                    ->as('donaturhadist')
                    ->withPivot('hadist_donatur_status')
                    ->withTimestamps();
    }
    public function berita(){
        return $this->belongsToMany('App\Models\Berita','berita_donatur','donatur_id','berita_id','id','id')
                    ->as('donaturberita')
                    ->withPivot('berita_donatur_status')
                    ->withTimestamps();
    }
    public function user(){
        return $this->hasOne('App\Models\User','email','donatur_email');
    }
    public function donasi()
    {
        return $this->hasOne('App\Models\Donasi','donatur_id','id');
    }
} 