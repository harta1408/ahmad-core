<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBayarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bayar', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('donasi_id')->unsigned();
            $table->foreign('donasi_id')->references('id')->on('donasi');
            $table->double('bayar_total',12,2)->default(0);
            $table->double('bayar_onkir',12,2)->default(0);
            $table->double('bayar_kode_unik',10)->default(0);
            $table->string('bayar_kode_voucer',30)->nullable();
            $table->double('bayar_disc',12,2)->default(0);
            $table->integer('bayar_termin')->default(1);
            $table->char('bayar_status',1)->default(0);
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
        Schema::dropIfExists('bayar');
    }
}
