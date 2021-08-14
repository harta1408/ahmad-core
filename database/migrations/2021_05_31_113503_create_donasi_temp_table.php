<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonasiTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donasi_temp', function (Blueprint $table) {
            $table->string('temp_donasi_no',6)->nullable();
            $table->integer('rekening_id')->default(0);
            $table->dateTime('temp_donasi_tanggal')->nullable();
            $table->integer('temp_donasi_jumlah_santri')->default(0);
            $table->double('temp_donasi_total_harga',12,2)->default(0);
            $table->double('temp_donasi_nominal',12,2)->default(0);
            $table->char('temp_donasi_cara_bayar',1)->default(0);
            $table->char('temp_donasi_random_santri',1)->default(0);
            $table->integer('temp_donasi_kode_unik',3)->default(0);
            $table->char('temp_donasi_status',1)->default(0);
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
        Schema::dropIfExists('donasi_temp');
    }
}
