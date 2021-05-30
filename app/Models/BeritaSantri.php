<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeritaSantri extends Model
{
    //
}
$table->bigInteger('berita_id')->unsigned();
$table->foreign('berita_id')->references('id')->on('berita');
$table->bigInteger('santri_id')->unsigned();
$table->foreign('santri_id')->references('id')->on('santri');
$table->char('berita_santri_status')->default(0);