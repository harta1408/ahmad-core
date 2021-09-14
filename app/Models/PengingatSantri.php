<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PengingatSantri extends Pivot
{
    #tabel berisi relasi pengingat dan santri untuk mengetahui respon santri
    #terhadap pengingat tersebut, akan di isi untuk jenis pengingat
    #4=Senin & Kamis 5=Jumat 6=online meeting 7=offline meeting 8=talkin dzikir saja
    #pegingat yang dikirimkan harus berbeda/ tidak boleh sama
    protected $table='pengingat_santri';
    protected $fillable=[
        'santri_id', //id santri
        'pengingat_id', //id pengingat
        'pengingat_santri_index', //nomor urut yang pengingat yang telah dikirimkan ke santri
        'pengingat_santri_respon', //0=no respon 1=berterimakasih 2=biasa saja 3=tidak suka
        'pengingat_santri_status', //0=tidak aktif 1=aktif 2=sudah selesai
    ];
}
