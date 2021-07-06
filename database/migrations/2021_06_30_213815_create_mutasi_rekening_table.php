<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMutasiRekeningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutasi_rekening', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('tanggal')->nullable();
            $table->string('deskripsi')->nullable();
            $table->double('nominal',18,2)->default(0);
            $table->char('tipe',2)->default('DB');
            $table->char('mutasi_id',2)->default('XX');
            $table->string('keterangan',100)->nullable();
            $table->char('status',1)->default(0);
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
        Schema::dropIfExists('mutasi_rekening');
    }
}
