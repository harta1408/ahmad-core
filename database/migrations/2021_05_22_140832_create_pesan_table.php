<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePesanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pesan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pembuat_id')->nullable();
            $table->char('pesan_entitas',1)->default(0);
            $table->text('pesan_isi')->nullable();
            $table->dateTime('pesan_waktu_kirim')->nullable();
            $table->char('pesan_status',1)->default(0);
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
        Schema::dropIfExists('pesan');
    }
}
