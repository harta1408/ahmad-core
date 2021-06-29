<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonasiCicilanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donasi_cicilan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('donasi_id')->unsigned();
            $table->foreign('donasi_id')->references('id')->on('donasi'); 
            $table->string('cicilan_ke',4)->nullable();
            $table->dateTime('cicilan_jatuh_tempo')->nullable();
            $table->string('cicilan_hijr',10)->nullable();
            $table->double('cicilan_nominal',12,2)->default(0);
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
        Schema::dropIfExists('donasi_cicilan');
    }
}
