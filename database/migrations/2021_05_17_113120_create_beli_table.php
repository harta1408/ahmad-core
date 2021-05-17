<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeliTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beli', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('donatur_id')->unsigned();
            $table->foreign('donatur_id')->references('id')->on('donatur');
            $table->dateTime('beli_tanggal')->nullable();
            $table->string('beli_catatan',50)->nullable();
            $table->double('beli_total_harga',12,2)->default(0);
            $table->double('beli_total_disc',12,2)->default(0);
            $table->double('beli_total_pajak',12,2)->default(0);
            $table->char('beli_status',1)->default(0);
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
        Schema::dropIfExists('beli');
    }
}
