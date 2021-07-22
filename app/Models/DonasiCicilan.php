<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonasiCicilan extends Model
{
    #berfungsi untuk menyimpan data cicilan berubah status menjadi bayar
    #setelah diterima pembayaran
    protected $table='donasi_cicilan';
    protected $fillable=[
        'donasi_id',
        'cicilan_ke', //cicilan ke
        'cicilan_jatuh_tempo', //waktu jatuh tempo
        'cicilan_hijr', //tanggal jatuh tempo dalam format hijriah
        'cicilan_nominal', //nominal cicilan
        'cicilan_status',  //0:not active 1:aktif belum bayar 2:aktif sudah bayar
    ];
    public function donasi(){
        return $this->hasOne('App\Models\Donasi','id','donasi_id');
    }
} 

