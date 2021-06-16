<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{

    protected $table='faq';
    protected $fillable=[
        'lembaga_id',
        'faq_tanya',
        'faq_jawab', 
        'faq_status', //0=tidak aktif 1=aktif 
    ];
}
