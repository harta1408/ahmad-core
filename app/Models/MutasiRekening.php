<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiRekening extends Model
{
 #tabel yang menyimpan master rekening bank
 protected $table='mutasi_rekening';
 protected $fillable=[
    "date",
    "description",
    "amount",
    "type",
    "balance",
    "created_at",
    "mutation_id",
    "note",
    "status",
 ];
}


