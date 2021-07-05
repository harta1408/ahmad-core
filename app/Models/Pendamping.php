<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendamping extends Model
{
    #tabel untuk menyimpan data pendamping, pendamping di input melalui sendiri
    #atau di input melalui lembaga dan otorisasi lembaga
    protected $table='pendamping';
    protected $fillable=[
        'pendamping_kode', //kode pendamping
        'pendamping_email', //alamat email pendamping
        'pendamping_nama', //nama pendamping
        'pendamping_tmp_lahir', //tempat lahir
        'pendamping_tgl_lahir',  //tanggal lahir
        'pendamping_nid',  //ktp, sim, kta 
        'pendamping_telepon', //nomor telepon
        'pendamping_gender', //jenis kelamin PRIA atau WANITA
        'pendamping_alamat', //alamat pendamping
        'pendamping_kode_pos', //kode pos
        'pendamping_kelurahan', //kelurahan
        'pendamping_kecamatan', //kecamatan
        'pendamping_kota', //kota
        'pendamping_provinsi', //provinsi
        'pendamping_lokasi_photo',//lokasi photo di server
        'pendamping_status_pegawai', //status kepegawaian pendampung
        'pendamping_honor', //honor yang diterima
        'pendamping_komisi', //komisi yang diterima
        'pendamping_rangkap', // untuk memeriksa apakah santri merangkap entitas lain (donatur/pendamping)
        'pendamping_min_referral', //hitungan minimal refferal untuk selalu mengingatkan
        'pendamping_status',  //0=tidak aktif 1=aktif 2=sudah dapat produk 3=dalam bimbingan
    ];
    public function user(){
        return $this->hasOne('App\Models\User','email','pendamping_email');
    }
    public function hadist(){
        return $this->belongsToMany('App\Models\Hadist','hadist_pendamping','pendamping_id','hadist_id','id','id')
                    ->as('pendampinghadist')
                    ->withPivot('hadist_pendamping_status')
                    ->withTimestamps();
    }
    public function berita(){
        return $this->belongsToMany('App\Models\Berita','berita_pendamping','pendamping_id','berita_id','id','id')
                    ->as('pendampingberita')
                    ->withPivot('berita_pendamping_status')
                    ->withTimestamps();
    }
}

    