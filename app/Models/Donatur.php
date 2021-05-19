<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donatur extends Model
{
    protected $table='donatur';
    protected $fillable=[
        'donatur_ktp',
        'donatur_nama',
        'donatur_mobile_no',
        'donatur_gender',
        'donatur_agama',
        'donatur_email',
        'donatur_photo',
        'donatur_kerja',
        'donatur_alamat',
        'donatur_status', //0=tidak aktif 1=aktif 2=sudah dapat produk 3=dalam bimbingan
    ];
    public function santri(){
        return $this->belongsToMany('App\Models\Donatur','donatur_santri','santri_id','donatur_id','id','id')
                    ->as('donatursantri')
                    ->withPivot('donatur_santri_status')
                    ->withTimestamps();
    }
 
}
