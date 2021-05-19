<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKuesionerSantriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kuesioner_santri', function (Blueprint $table) {
            $table->bigInteger('santri_id')->unsigned();
            $table->foreign('santri_id')->references('id')->on('santri');
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
        Schema::dropIfExists('kuesioner_santri');
    }
}
