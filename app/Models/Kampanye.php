<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kampanye extends Model
{
    //tabel yang digunakan untuk menyimpan kampanye, yaitu notifikasi yang
    //dikirimkan pada saat proses referral
    protected $table='kampanye';
    protected $fillable=[
        'kampanye_isi',  //isi kampanye
        'kampanye_jenis', //jenis kampanye
        'kampanye_lokasi_gambar', //lokasi gambar di server
        'kampanye_lokasi_video', //lokasi video di server
        'kampanye_status', //0=tidak aktif 1=aktif 
    ];
}

   