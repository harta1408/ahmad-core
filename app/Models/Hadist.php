<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hadist extends Model
{
    protected $table='hadist';
    protected $fillable=[
        'hadist_judul', //judul hadist
        'hadist_isi', //isi hadist
        'hadist_jenis', //1=hadist 2-kampanye 3=kampanye broadcast wa
        'hadist_kirim', //0=tidak aktif 1=setiap hari 2=mingguan 3=bulanan 4=waktu tertenu
        'hadist_waktu_kirim', //kirim waktu tertentu status 4
        'hadist_lokasi_gambar', //lokasi gambar
        'hadist_lokasi_video', //lokasi video
        'hadist_web_link', //alamat web hadist (khusud web)
        'hadist_status', //0=hapus 1=aktif 
    ];
    public function donatur(){
        return $this->belongsToMany('App\Models\Donatur','hadist_donatur','hadist_id','donatur_id','id','id')
                    ->as('hadistdonatur')
                    ->withPivot('hadist_donatur_status')
                    ->withTimestamps();
    }
    public function santri(){
        return $this->belongsToMany('App\Models\Santri','hadist_santri','hadist_id','santri_id','id','id')
                    ->as('hadistsantri')
                    ->withPivot('hadist_santri_status')
                    ->withTimestamps();
    }
    public function pendamping(){
        return $this->belongsToMany('App\Models\Pendamping','hadist_pendamping','hadist_id','pendamping_id','id','id')
                    ->as('hadistpendamping')
                    ->withPivot('hadist_pedamping_status')
                    ->withTimestamps();
    }
}
