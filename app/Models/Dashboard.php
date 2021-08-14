<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    protected $fillable=[
        'dash_donasi_harian', //donasi yang dananya sudah diterima (hari aktif)
        'dash_donasi_tagihan', //donasi yang seharusnya di terima (hari aktif)
        'dash_bimbingan_jumlah', //jumlah bimbingan
        'dash_donasi_santri_nambah', //penambahan jumlah santri
        'dash_chart_donasi_status', //data chart berdasarkan status donasi
        'dash_chart_santri_status', //data chart santri tersedia
    ];
}
