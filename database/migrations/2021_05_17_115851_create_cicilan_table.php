<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCicilanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cicilan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('donatur_id')->unsigned();
            $table->foreign('donatur_id')->references('id')->on('donatur');
            $table->bigInteger('bayar_id')->unsigned();
            $table->foreign('bayar_id')->references('id')->on('bayar');
            $table->string('cicilan_ke',2)->nullable();
            $table->double('cicilan_jumlah',12,2)->default(0);
            $table->char('cicilan_status',1)->default(0);
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
        Schema::dropIfExists('cicilan');
    }
}
