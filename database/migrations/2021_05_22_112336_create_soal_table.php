<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('materi_id')->unsigned();
            $table->foreign('materi_id')->references('id')->on('materi');
            $table->text('soal_deskripsi')->nullable();
            $table->char('soal_jenis',1)->default(0);
            $table->string('soal_pilihan_a',50)->nullable();
            $table->string('soal_pilihan_b',50)->nullable();
            $table->string('soal_pilihan_c',50)->nullable();
            $table->string('soal_pilihan_d',50)->nullable();
            $table->integer('soal_nilai_maksimum')->default(0);
            $table->integer('soal_nilai_minimum')->default(0);
            $table->char('soal_status',1)->default(0);
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
        Schema::dropIfExists('soal');
    }
}
