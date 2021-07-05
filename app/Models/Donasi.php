<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    #berisi data/ informasi donasi, jika memilih pembayaran cicilan, maka
    #status pengingat akan sesuai dengan waktu donasi yang di pilih
    #jika donatur membayar penuh, maka di berikan opsi apakah menginkan notifikasi
    protected $table='donasi';
    protected $fillable=[
        'donasi_no', //no bukti/ referensi donasi untuk pelacakan oleh donatur
        'donatur_id', //id donatur
        'rekening_id', //kode rekening
        'donasi_tanggal', //tanggal donasi(mulai donasi)
        'donasi_catatan', //jika ada catatan
        'donasi_jumlah_santri', //jumlah santri penerima manfaat
        'donasi_total_harga', //total transaksi
        'donasi_nominal', //nominal donasi yang ingin dibayarkan
        'donasi_pengingat_harian', //donatur menginginkan notifikasi harian
        'donasi_pengingat_mingguan', //donatur meninginkan notifikasi mingguan
        'donasi_pengingat_bulanan', //donatur menginginkan notifikasi bulanan
        'donasi_cara_bayar', //cara pembayaran 1=harian, 2=mingguan, 3=bulanan 4=tunai
        'donasi_status', //0=tidak aktif/batal  1=aktif belum bayar 2=aktif sudah bayar 3=sudah tersalurkan ke santri 4=berhenti(khusus cicilan)

        #dummy 
        'donasi_donatur_nama',
        'donasi_santri_id',
        'donasi_santri_nama',
    ];
    public function produk(){
        return $this->belongsToMany('App\Models\Produk','donasi_produk','donasi_id','produk_id','id','id')
                    ->as('donasiproduk')
                    ->withPivot('donasi_produk_jml','donasi_produk_harga','donasi_produk_total')
                    ->withTimestamps();
    }
    public function donatur(){
        return $this->hasOne('App\Models\Donatur','id','donatur_id');
    }
    public function bayar(){
        return $this->hasOne('App\Models\Bayar','donasi_id','id');
    }
    public function rekeningbank(){
        return $this->hasOne('App\Models\RekeningBank','id','rekening_id');
    }
    public function cicilan(){
        return $this->hasMany('App\Models\DonasiCicilan','donasi_id','id');
    }
}

 