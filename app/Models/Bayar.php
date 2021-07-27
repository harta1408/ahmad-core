<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bayar extends Model
{
    #tabel yang menyimpan pembayaran, di eksekusi pada saat donatur
    #melakukan pembayaran tunai, atau dalam bentuk cicilan
    #tabel ini melakukan pengecekan ke Bank, ketika pengecekan sukses
    #maka status akan di ubah dari 1 menjadi 2 sudah bayar
    #jika gagal bayar status menjadi 3
    protected $table='bayar';
    protected $fillable=[
        'cicilan_id', //id cicilan
        'bayar_tanggal', //tanggal pembayaran diterima
        'bayar_total', //total pembayaran, termasuk kode unik jika berupa cicilan maka sebesar nilai cicilannya
        'bayar_kode_unik', //kode unik untuk melakukan tracing ke rekening bank
        'bayar_kode_voucer', //jika ada promo
        'bayar_disc', //nilai potongan harga
        'bayar_onkir', //nilai ongkos kirim
        'bayar_status',  //0:tidak aktif/batal 1:belum bayar 2:sudah bayar 3:gagal bayar
    ];
    public function cicilan(){
        return $this->hasOne('App\Models\DonasiCicilan','id','cicilan_id');
    }
}

