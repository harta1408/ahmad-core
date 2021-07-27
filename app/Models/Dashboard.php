<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    protected $fillable=[
        'dash_donasi_terima', //donasi yang dananya sudah diterima (hari aktif)
        'dash_donasi_tagihan', //donasi yang seharusnya di terima (hari aktif)

        'dash_donasi_nilai',
        'dash_donasi_jumlah',
        'dash_bimbingan_jumlah',
        'dash_santri_otorisasi',
    ];
}
