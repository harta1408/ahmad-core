<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBimbinganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bimbingan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('santri_id')->unsigned();
            $table->foreign('santri_id')->references('id')->on('santri');
            $table->bigInteger('pendamping_id')->unsigned();
            $table->foreign('pendamping_id')->references('id')->on('pendamping');
            $table->integer('produk_id')->unsigned();
            $table->foreign('produk_id')->references('id')->on('produk');            
            $table->date('bimbingan_mulai')->nullable();
            $table->date('bimbingan_berakhir')->nullable();
            $table->string('bimbingan_nilai_angka',3)->nullable();
            $table->string('bimbingan_nilai_huruf',3)->nullable();
            $table->string('bimbingan_predikat',10)->nullable();
            $table->string('bimbingan_catatan')->nullable();
            $table->char('bimbingan_status',1)->default(0);
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
        Schema::dropIfExists('bimbingan');
    }
}
