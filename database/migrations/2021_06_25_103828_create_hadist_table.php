<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHadistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hadist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('hadist_judul')->nullable();
            $table->text('hadist_isi')->nullable();
            $table->char('hadist_jenis',1)->default(0);
            $table->char('hadist_kirim',1)->default(0);
            $table->dateTime('hadist_waktu_kirim')->nullable();
            $table->string('hadist_lokasi_gambar')->nullable();
            $table->string('hadist_lokasi_video')->nullable();
            $table->char('hadist_status',1)->default(0);
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
        Schema::dropIfExists('hadist');
    }
}
