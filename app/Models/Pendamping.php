<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendamping extends Model
{
    #tabel untuk menyimpan data pendamping, pendamping di input melalui sendiri
    #atau di input melalui lembaga dan otorisasi lembaga
    protected $table='pendamping';
    protected $fillable=[
        'pendamping_email', //alamat email pendamping
        'pendamping_nama', //nama pendamping
        'pendamping_kode', //kode pendamping
        'pendamping_nid',  //ktp, sim, kta 
        'pendamping_telepon', //nomor telepon
        'pendamping_gender', //jenis kelamin PRIA atau WANITA
        'pendamping_alamat', //alamat pendamping
        'pendamping_kode_pos', //kode pos
        'pendamping_kelurahan', //kelurahan
        'pendamping_kecamatan', //kecamatan
        'pendamping_kota', //kota
        'pendamping_provinsi', //provinsi
        'pendamping_status_pegawai', //status kepegawaian pendampung
        'pendamping_honor', //honor yang diterima
        'pendamping_komisi', //komisi yang diterima
        'pendamping_rangkap', // untuk memeriksa apakah santri merangkap entitas lain (donatur/pendamping)
        'pendamping_min_referral', //hitungan minimal refferal untuk selalu mengingatkan
        'pendamping_status',  //0=tidak aktif 1=aktif 2=sudah dapat produk 3=dalam bimbingan
    ];
}

    