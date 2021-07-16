<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengingatSantriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengingat_santri', function (Blueprint $table) {
            $table->bigInteger('santri_id')->unsigned();
            $table->foreign('santri_id')->references('id')->on('santri'); 
            $table->integer('pengingat_id')->unsigned();
            $table->foreign('pengingat_id')->references('id')->on('pengingat'); 
            $table->char('pengingat_santri_index',1)->default(1);
            $table->char('pengingat_santri_respon',1)->default(1);
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
        Schema::dropIfExists('pengingat_santri');
    }
}
