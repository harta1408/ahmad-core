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
        'referral_id', //id pengirim
        'referral_entitas_kode', //entitas pengirim
        'referral_jenis', //jenis referal yang dikirim
        'referral_telepon', //nomor telepon yang di tuju        
        'referral_web_link', //link referral yang dikirim
        'referral_status', //0=tidak aktif  1=dikirimkan 2=di follow up 3-tidak di followup
    ];

}

