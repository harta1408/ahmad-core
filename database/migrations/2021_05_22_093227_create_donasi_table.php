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
            $table->integer('rekening_id')->unsigned();
            $table->foreign('rekening_id')->references('id')->on('rekening_bank');
            $table->string('donasi_no',10)->nullable();
            $table->dateTime('donasi_tanggal')->nullable();
            $table->integer('donasi_jumlah_santri')->default(0);
            $table->integer('donasi_sisa_santri')->default(0);
            $table->double('donasi_total_harga',12,2)->default(0);
            $table->double('donasi_nominal',12,2)->default(0);
            $table->char('donasi_pengingat_harian',1)->default(0);
            $table->char('donasi_pengingat_mingguan',1)->default(0);
            $table->char('donasi_pengingat_bulanan',1)->default(0);
            $table->char('donasi_random_santri',1)->default(0);
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
