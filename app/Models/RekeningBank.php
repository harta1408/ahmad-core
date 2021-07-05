<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningBank extends Model
{
 #tabel yang menyimpan master rekening bank
 protected $table='rekening_bank';
 protected $fillable=[
     'rekening_nama', //nama tercantum pada rekening
     'rekening_no', //nomor rekening bank
     'rekening_nama_bank', //nama bank
     'rekening_status', //0=tidak aktif, 1=aktif
 ];
}
