<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeritaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berita', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('berita_isi')->nullable();
            $table->char('berita_jenis',1)->default(0);
            $table->char('berita_entitas',1)->default(0);
            $table->text('berita_lokasi_gambar')->nullable();
            $table->text('berita_lokasi_video')->nullable();
            $table->char('berita_status',1)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('berita');
    }
}
