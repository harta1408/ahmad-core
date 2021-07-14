<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hadiah extends Model
{
    protected $table='hadiah';
    protected $fillable=[
        'hadiah_nama', //nama hadiah
        'hadiah_jenis', //1=uang tunai 2=produk
        'hadiah_no_seri', //nomor seri, jika berupa voucher
        'hadiah_nilai', //nilai dalam bentuk referral
        'hadiah_nominal', //nilai dalam bentuk rupiah
        'hadiah_mulai', //mulai berlaku
        'hadiah_akhir', //ekspired
        'hadiah_status', //0=non aktif 1=aktif 
    ];
}

