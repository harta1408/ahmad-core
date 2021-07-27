<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    #berisi data/ informasi donasi, jika memilih pembayaran cicilan, maka
    #status pengingat akan sesuai dengan waktu donasi yang di pilih
    #jika donatur membayar penuh, maka di berikan opsi apakah menginkan notifikasi

    #mekanisme pengacakan santri berada di modul random service untuk donasi yang telah mencapai
    #angka lebih dari 200rb
    #untuk donasi tunai (cara bayar=4), di cek pada field random santri, jika tunai dan random santri bernilai 1 maka
    #pengacakan dilakukan melalui modul Donasi Controller
    #pemeriksaan pembayaran dilakukan melalui Random Service, pembayaran manual melalui modul Donasi Controller
    protected $table='donasi';
    protected $fillable=[
        'donasi_no', //no bukti/ referensi donasi untuk pelacakan oleh donatur
        'donatur_id', //id donatur
        'rekening_id', //kode rekening
        'donasi_tanggal', //tanggal donasi(mulai donasi)
        'donasi_catatan', //jika ada catatan
        'donasi_jumlah_santri', //jumlah santri penerima manfaat
        'donasi_sisa_santri', //sisa santri yang belum tersalurkan, nilai awal sama dengan jumlah santri nilai akhir 0
        'donasi_total_harga', //total transaksi
        'donasi_nominal', //nominal donasi yang ingin dibayarkan
        'donasi_pengingat_harian', //donatur menginginkan notifikasi harian
        'donasi_pengingat_mingguan', //donatur meninginkan notifikasi mingguan
        'donasi_pengingat_bulanan', //donatur menginginkan notifikasi bulanan
        'donasi_cara_bayar', //cara pembayaran 1=harian, 2=mingguan, 3=bulanan 4=tunai
        'donasi_random_santri', //0=random santri by system, 1=random santro by helpdesk, request by donatur
        'donasi_status', //0=tidak aktif/batal  1=aktif belum lunas 2=sudah lebih dari 200rb(eligible untuk random santri) 3=sudah lunas bayar TUNAI  4=macet(khusus cicilan) 5=sudah Lunas selesai tersalurkan ke santri
                         //jika status=3 dan cara bayar 4 ->eligible untuk random santri
        #dummy 
        'donasi_donatur_nama',
        'donasi_santri_id',
        'donasi_santri_nama',
        'donasi_tanggal_akhir',
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
    public function santri(){
        return $this->belongsToMany('App\Models\Santri','donatur_santri','donasi_id','santri_id','id','id')
                    ->as('donasisantri')
                    ->withPivot('donasi_id','pendamping_id','donatur_santri_status')
                    ->withTimestamps();
    }
    // public function bayar(){
    //     return $this->hasOne('App\Models\Bayar','donasi_id','id');
    // }
    public function rekeningbank(){
        return $this->hasOne('App\Models\RekeningBank','id','rekening_id');
    }
    public function cicilan(){
        return $this->hasMany('App\Models\DonasiCicilan','donasi_id','id');
    }
}

 