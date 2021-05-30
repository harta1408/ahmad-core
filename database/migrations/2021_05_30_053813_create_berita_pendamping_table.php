<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeritaPendampingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berita_pendamping', function (Blueprint $table) {
            $table->bigInteger('berita_id')->unsigned();
            $table->foreign('berita_id')->references('id')->on('berita');
            $table->bigInteger('pendamping_id')->unsigned();
            $table->foreign('pendamping_id')->references('id')->on('pendamping');
            $table->char('berita_pendamping_status')->default(0);
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
        Schema::dropIfExists('berita_pendamping');
    }
}
