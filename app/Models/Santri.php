<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    protected $table='santri';
    protected $fillable=[
        'santri_kode', 
        'santri_nama',
        'santri_tmp_lahir', 
        'santri_tgl_lahir',  
        'santri_gender',
        'santri_mobile_no',
        'santri_email',
        'santri_alamat',
        'santri_kode_pos',
        'santri_kelurahan',
        'santri_kota',
        'santri_kecamatan',
        'santri_provinsi',
        'santri_rangkap', // untuk memeriksa apakah santri merangkap entitas lain (donatur/pendamping)
        'santri_status', //0=tidak aktif 1=aktif 2=sudah dapat produk 3=dalam bimbingan
    ];


    public function donatur(){
        return $this->belongsToMany('App\Models\Donatur','donatur_santri','donatur_id','santri_id','id','id')
                    ->as('santridonatur')
                    ->withPivot('donatur_santri_status')
                    ->withTimestamps();
    }
 
}
