<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuesioner extends Model
{
    protected $table='kuesioner';
    protected $fillable=[
        'kuesioner_tanya',
        'kuesioner_bobot_yes',
        'kuesioner_bobot_no',
        'kuesioner_status', //0=tidak aktif 1=aktif 2=sudah dapat produk 3=dalam bimbingan
    ];
    public function santri(){
        return $this->belongsToMany('App\Models\Kuesioner','kuesioner_santri','kuesioner_id','santri_id','id','id')
                    ->as('kuesionersantri')
                    ->withPivot('kuesioner_jawab','kuesioner_nilai')
                    ->withTimestamps();
    }
}

