<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donasi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('donatur_id')->unsigned();
            $table->foreign('donatur_id')->references('id')->on('donatur');
            $table->string('donasi_no',10)->nullable();
            $table->dateTime('donasi_tanggal')->nullable();
            $table->string('donasi_catatan',50)->nullable();
            $table->double('donasi_total_harga',12,2)->default(0);
            $table->char('donasi_pengingat_harian',1)->default(0);
            $table->char('donasi_pengingat_mingguan',1)->default(0);
            $table->char('donasi_pengingat_bulanan',1)->default(0);
            $table->char('donasi_cara_bayar',1)->default(0);
            $table->char('donasi_status',1)->default(0);
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
        Schema::dropIfExists('donasi');
    }
}
