<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donatur extends Model
{
    #tabel berisi informasi donatur
    protected $table='donatur';
    protected $fillable=[
        'donatur_kode',
        'donatur_id', //nomor ktp, sim. kta
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
        'donatur_status', //0=tidak aktif 1=aktif 2=sudah dapat produk 3=dalam bimbingan
    ];
    public function santri(){
        return $this->belongsToMany('App\Models\Donatur','donatur_santri','donatur_id','santri_id','id','id')
                    ->as('donatursantri')
                    ->withPivot('donatur_santri_status')
                    ->withTimestamps();
    }
 
} 