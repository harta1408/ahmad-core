<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBimbinganMateriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bimbingan_materi', function (Blueprint $table) {
            $table->bigInteger('bimbingan_id')->unsigned();
            $table->foreign('bimbingan_id')->references('id')->on('bimbingan');
            $table->integer('materi_id')->unsigned();
            $table->foreign('materi_id')->references('id')->on('materi');
            $table->string('bimbingan_materi_angka',3)->nullable();
            $table->string('bimbingan_materi_huruf',3)->nullable();
            $table->string('bimbingan_materi_catatan')->nullable();
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
        Schema::dropIfExists('bimbingan_materi');
    }
}
