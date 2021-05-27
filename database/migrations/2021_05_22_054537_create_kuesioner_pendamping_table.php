<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKuesionerPendampingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kuesioner_pendamping', function (Blueprint $table) {
            $table->bigInteger('pendamping_id')->unsigned();
            $table->foreign('pendamping_id')->references('id')->on('pendamping');
            $table->integer('kuesioner_id')->unsigned();
            $table->foreign('kuesioner_id')->references('id')->on('kuesioner');
            $table->string('kuesioner_jawab',5)->default('TIDAK');
            $table->integer('kuesioner_nilai')->default(0);
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
        Schema::dropIfExists('kuesioner_pendamping');
    }
}
