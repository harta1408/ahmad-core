<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendampingSantri extends Pivot
{
    #tabel relasi hubungan pendamping dan santri, setiap pendamping dapat 
    #mendampingi lebih dari satu santri
    #setiap santri bisa saja mendapatkan bimbingan dari pendampaing yang berbeda untuk 
    #materi/produk yang berbeda
    protected $table='pendamping_santri';
    protected $fillable=[
        'santri_id', //id santri
        'pendamping_id', //id pendamping
        'materi_id', //id materi
        'materi_nilai_angka', //nilai bimbingan untuk materi tsb berupa angka
        'materi_nilai_huruf', //nilai bimbingan untuk materi tsb berupa huruf
        'materi_catatan_nilai', //catatan pendamping jika ada
    ];
}
