<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HadistDonatur extends Pivot
{
    protected $table='hadist_donatur';
    protected $fillable=[
        'hadist_id', //id hadist
        'donatur_id', //id donatur
        'hadist_donatur_status',  //0:belum di baca 1:sudah di baca 2:dihapus
    ];
}
