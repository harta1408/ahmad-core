<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeritaSantriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berita_santri', function (Blueprint $table) {
            $table->bigInteger('berita_id')->unsigned();
            $table->foreign('berita_id')->references('id')->on('berita');
            $table->bigInteger('santri_id')->unsigned();
            $table->foreign('santri_id')->references('id')->on('santri');
            $table->char('berita_santri_status')->default(0);
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
        Schema::dropIfExists('berita_santri');
    }
}
