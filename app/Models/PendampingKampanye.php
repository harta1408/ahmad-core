<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendampingKampanye extends Pivot
{
    #tabel relasi pendamping dan kampanye untuk menyimpan kampanye atau pesan
    #yang dikirim oleh pendamping ke donatur, secara default setiap kampanye di
    #buat, maka akan dibuatkan link referallnya, yang nantinya akan di kirim oleh
    #pedamping kepada calon donatur
    protected $table='pendamping_kampanye';
    protected $fillable=[
        'pendamping_id', //id pendamping
        'kampanye_id', //id kampanye
        'referral_web_link', //alamat link referral, berisi id pendamping dan id kampanye
        'referral_status', //referral yang sedang aktif 0=non aktif, 1=aktif
    ];
}
 