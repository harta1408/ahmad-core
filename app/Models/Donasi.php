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
        'donasi_no', //no bukti/ referensi donasi untuk pelacakan oleh donatur
        'donatur_id', //id donatur
        'donasi_tanggal', //tanggal donasi(mulai donasi)
        'donasi_catatan', //jika ada catatan
        'donasi_jumlah_santri', //jumlah santri penerima manfaat
        'donasi_total_harga', //total transaksi
        'donasi_pengingat_harian', //donatur menginginkan notifikasi harian
        'donasi_pengingat_mingguan', //donatur meninginkan notifikasi mingguan
        'donasi_pengingat_bulanan', //donatur menginginkan notifikasi bulanan
        'donasi_cara_bayar', //cara pembayaran 1=harian, 2=mingguan, 3=bulanan 4=tunai
        'donasi_status', //0=tidak aktif/batal  1=aktif 2=berhenti(khusus cicilan)
    ];
    public function produk(){
        return $this->belongsToMany('App\Models\Produk','donasi_produk','donasi_id','produk_id','id','id')
                    ->as('donasiproduk')
                    ->withPivot('donasi_produk_jml','donasi_produk_harga','donasi_produk_total')
                    ->withTimestamps();
    }
    public function donatur()
    {
        return $this->belongTo('App\Models\Donatur','id','donatur_id');
    }
}

 