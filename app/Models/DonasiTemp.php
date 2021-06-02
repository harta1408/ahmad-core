<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonasiTemp extends Model
{
    protected $table='donasi_temp';
    protected $fillable=[
        'rekening_id',
        'temp_donasi_no',
        'temp_donasi_tanggal',
        'temp_donasi_jumlah_santri', //jumlah santri penerima manfaat donasi
        'temp_donasi_tagih', //nilai yang di tagihkan sesuai cara bayar
        'temp_donasi_total_harga', //total harga
        'temp_donasi_cara_bayar', // 1=harian, 2=mingguan, 3=bulanan 4=tunai
        'temp_donasi_status',
    ];
    public function produk(){
        return $this->belongsToMany('App\Models\Produk','donasi_temp_produk','temp_donasi_no','produk_id','temp_donasi_no','id')
                    ->as('donasiproduktemp')
                    ->withPivot('temp_donasi_produk_jml','temp_donasi_produk_harga','temp_donasi_produk_total')
                    ->withTimestamps();
    }
}


