<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengingatDonaturTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengingat_donatur', function (Blueprint $table) {
            $table->bigInteger('donatur_id')->unsigned();
            $table->foreign('donatur_id')->references('id')->on('donatur'); 
            $table->integer('pengingat_id')->unsigned();
            $table->foreign('pengingat_id')->references('id')->on('pengingat'); 
            $table->char('pengingat_donatur_respon',1)->default(0);
            $table->char('pengingat_donatur_status')->default(0);
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
        Schema::dropIfExists('pengingat_donatur');
    }
}
