<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    #pesan dikirimkan langsung ke sistem  AHMaD dan akan muncul berupa notifikasi/ lonceng
    #bisa dikirimkan antar entitas, bisa otomatis oleh sistem, bisa juga dikirim broadcast 
    #oleh lembaga/helpdesk melalui dashboard
    protected $table='pesan';
    protected $fillable=[
        'pesan_pembuat_id', //user pembuat pesan, 0 dari system
        'pesan_tujuan_id', //user tujuan pesan
        'pesan_tujuan_entitas', //1=donatur 2=santri 3=pendamping
        'pesan_isi',//isi pesan
        'pesan_waktu_kirim', //waktu pengiriman
        'pesan_status', //0=not aktif 1=belum di baca 2=sudah di baca 3=dihapus
    ];

    public function pembuat(){
        return $this->hasOne('App\Models\User','id','pesan_pembuat_id');
    }
    public function tujuan(){
        return $this->hasOne('App\Models\User','id','pesan_tujuan_id');
    }
}


 