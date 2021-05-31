<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Referral extends Model
{
    #berfungsi mencatat setiap link referral yang dikirim untuk mengetahui
    #apakah ditindaklanjuti atau tidak oleh penerima
    protected $table='referral';
    protected $fillable=[
        'berita_id', //id berita
        'referral_id_pengirim', //id pengirim (kode)
        'referral_id_penerima', //id penerima (kode)
        'referral_entitas_pengirim', 
        'referral_entitas_penerima',
        'referral_telepon',
        'referral_web_link',
        'referral_status', //0=tidak aktif 1=di follow up 2-tidak di followup
    ];
}

