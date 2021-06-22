<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMateriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('produk_id')->unsigned();
            $table->foreign('produk_id')->references('id')->on('produk');
            $table->string('materi_nama')->nullable();
            $table->text('materi_deskripsi')->nullable();
            $table->string('materi_lokasi_gambar')->nullable();
            $table->string('materi_lokasi_video')->nullable();
            $table->string('materi_level',20)->nullable();
            $table->integer('materi_bobot')->default(0);
            $table->char('materi_status',1)->default(0);
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
        Schema::dropIfExists('materi');
    }
}
