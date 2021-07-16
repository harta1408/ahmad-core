<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengingatPendamping extends Model
{
    #tabel berisi relasi pengingat dan pendamping untuk mengetahui respon pendamping
    #terhadap pengingat tersebut, akan di isi untuk jenis pengingat
    #4=Senin & Kamis 5=Jumat 6=online meeting 7=offline meeting 8=talkin dzikir saja
    #berbeda dengan donatur dan santi, tabel ini berisi pengingat yang telah dikirimkan 
    #kapada santri mana saja dan bagaimana respon santri
    protected $table='pengingat_pendamping';
    protected $fillable=[
        'pendamping_id', //id pendamping
        'pengingat_id', //id pengingat
        'santri_id', // id santri
        'santri_respon', //0=no respon 1=di baca, bersedia ikut, 2=di baca, tidak bersedia
    ];
}
