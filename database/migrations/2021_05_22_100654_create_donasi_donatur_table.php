<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonasiDonaturTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donasi_donatur', function (Blueprint $table) {
            $table->bigInteger('donatur_id')->unsigned();
            $table->foreign('donatur_id')->references('id')->on('donatur');
            $table->bigInteger('donasi_id')->unsigned();
            $table->foreign('donasi_id')->references('id')->on('donasi');
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
        Schema::dropIfExists('donasi_donatur');
    }
}
