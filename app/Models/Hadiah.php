<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hadiah extends Model
{
    #tabel untuk menyimpan hadiah yang aktif, setiap saat hanya satu hadiah yang aktif
    #bisa jadi seorang pendamping mendampatkan hadiah berbeda, tergantung dari mana
    #hadiah yang aktif
    protected $table='hadiah';
    protected $fillable=[
        'hadiah_nama', //nama hadiah
        'hadiah_jenis', //1=uang tunai 2=produk
        'hadiah_no_seri', //nomor seri, jika berupa voucher
        'hadiah_nilai', //nilai dalam bentuk referral
        'hadiah_nominal', //nilai dalam bentuk rupiah
        'hadiah_mulai', //mulai berlaku
        'hadiah_akhir', //ekspired
        'hadiah_status', //0=hapus 1=tidak aktif 2=aktif digunakan
    ];
}

