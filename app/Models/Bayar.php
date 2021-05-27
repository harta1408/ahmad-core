<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bayar extends Model
{
    #tabel yang menyimpan pembayaran, di eksekusi pada saat donatur
    #melakukan pembayaran tunai, atau dalam bentuk cicilan
    #tabel ini melakukan pengecekan ke Bank
    protected $table='bayar';
    protected $fillable=[
        'donatur_id', //id donatur
        'bayar_total', //total pembayaran, jika berupa cicilan maka sebesar nilai cicilannya
        'bayar_kode_unik', //kode unik untuk melakukan tracing ke rekening bank
        'bayar_kode_voucer', //jika ada promo
        'bayar_disc', //nilai potongan harga
        'bayar_onkir', //nilai ongkos kirim
        'bayar_status',  //0:tidak aktif/batal 1:aktif
    ];
}

