<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoalSantriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soal_santri', function (Blueprint $table) {
            $table->integer('soal_id')->unsigned();
            $table->foreign('soal_id')->references('id')->on('soal');
            $table->bigInteger('santri_id')->unsigned();
            $table->foreign('santri_id')->references('id')->on('santri');
            $table->text('soal_jawaban_essay')->nullable();
            $table->string('soal_jawaban_pilihan',50)->nullable();
            $table->integer('soal_santri_nilai')->default(0);
            $table->char('soal_santri_status',1)->default(0);
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
        Schema::dropIfExists('soal_santri');
    }
}
