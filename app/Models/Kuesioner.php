<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuesioner extends Model
{
    #tabel berisi kuesioner/ pertanyaan yang diberikan kepada calon santri atau
    #calon pendamping dan sebagai bahan pada proses otorisasi
    protected $table='kuesioner';
    protected $fillable=[
        'kuesioner_tujuan', //2=santri 3=pendamping (supaya konsisten)
        'kuesioner_tanya', //pertanyaan
        'kuesioner_bobot_yes', //bobot jawaban YA
        'kuesioner_bobot_no', //bobot jawaban TIDAK
        'kuesioner_status', //0=tidak aktif 1=aktif 2=sudah dapat produk 3=dalam bimbingan
    ];
    public function santri(){
        return $this->belongsToMany('App\Models\Kuesioner','kuesioner_santri','kuesioner_id','santri_id','id','id')
                    ->as('kuesionersantri')
                    ->withPivot('kuesioner_jawab','kuesioner_nilai')
                    ->withTimestamps();
    }
} 