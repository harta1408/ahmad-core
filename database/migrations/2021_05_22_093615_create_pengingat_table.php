<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengingatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengingat', function (Blueprint $table) {
            $table->increments('id');
            $table->char('pengingat_entitas',1)->default(1);
            $table->char('pengingat_index',2)->default(1);
            $table->string('pengingat_judul')->nullable();
            $table->text('pengingat_isi')->nullable();
            $table->char('pengingat_jenis',1)->default(0);
            $table->text('pengingat_lokasi_gambar')->nullable();
            $table->text('pengingat_lokasi_video')->nullable();
            $table->char('pengingat_status',1)->default(0);
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
        Schema::dropIfExists('pengingat');
    }
}
