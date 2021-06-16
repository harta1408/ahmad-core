<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    #pesan dikirimkan melalui nomor wa yang terdaftar dengan tujuan donatur, santri dan pendamping
    #atau masing masing, pesan di broadcast oleh lembaga dan  tabel ini berfungsi untuk
    #menyimpan pesan yang dikirimkan
    #tabel pesan digunakan juga untuk menyimpan informasi pesan kajian dari pendamping
    #untuk dikirimkan ke santri
    protected $table='pesan';
    protected $fillable=[
        'pembuat_id', //user pembuat pesan
        'pesan_entitas', //1=donatur 2=santri 3=pendamping 
        'pesan_judul', //judul pesan
        'pesan_isi',//isi pesan
        'pesan_waktu_kirim', //waktu pengiriman
        'pesan_status', //0=not aktif 1=aktif
    ];
}


 