<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKuesionerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kuesioner', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('kuesioner_tanya')->nullable();
            $table->integer('kuesioner_bobot_yes')->default(0);
            $table->integer('kuesioner_bobot_no')->default(0);
            $table->char('kuesioner_status',1)->default(0);
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
        Schema::dropIfExists('kuesioner');
    }
}
