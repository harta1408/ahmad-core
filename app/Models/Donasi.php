<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    #berisi data/ informasi donasi, jika memilih pembayaran cicilan, maka
    #status pengingat akan sesuai dengan waktu donasi yang di pilih
    #jika donatur membayar tunai, maka di berikan opsi apakah menginkan notifikasi
    protected $table='donasi';
    protected $fillable=[
        'donatur_id', //id donatur
        'donasi_tanggal', //tanggal donasi(mulai donasi)
        'donasi_catatan', //jika ada catatan
        'donasi_total_harga', //total transaksi
        'donasi_pengingat_harian', //donatur menginginkan notifikasi harian
        'donasi_pengingat_mingguan', //donatur meninginkan notifikasi mingguan
        'donasi_pengingat_bulanan', //donatur menginginkan notifikasi bulanan
        'donasi_status', //0=tidak aktif/batal  1=aktif 2=berhenti(khusus cicilan)
    ];
}

 