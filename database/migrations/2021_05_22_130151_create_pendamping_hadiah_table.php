<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendampingHadiahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendamping_hadiah', function (Blueprint $table) {
            $table->bigInteger('pendamping_id')->unsigned();
            $table->foreign('pendamping_id')->references('id')->on('pendamping');
            $table->integer('hadiah_id')->unsigned();
            $table->foreign('hadiah_id')->references('id')->on('hadiah');
            $table->double('pendamping_hadiah_nilai')->default(0);
            $table->char('pendamping_hadiah_status',1)->default(0);
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
        Schema::dropIfExists('pendamping_hadiah');
    }
}
