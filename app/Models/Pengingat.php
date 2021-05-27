<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengingat extends Model
{
    #tabel pengingat berisi data mutiara hadist, doa, atau kata kata yang bermakna
    #mendalam dilengkapi gambar dan video, yang dikirimkan pada saat tertentu
    protected $table='pengingat';
    protected $fillable=[
        'pengingat_isi', //isi pengingat
        'pengingat_jenis', //1=sedekah subuh, 2=sedekah jumat 3=sedekah yaumul bidh 4=harian 5=mingguan 6=bulanan 7=hari raya
        'pengingat_lokasi_gambar', //lokasi gambar
        'pengingat_lokasi_video', //lokasi video
        'pengingat_status', //0=non aktif 1=aktif
    ];
}
