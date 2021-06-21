<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('berita_id')->unsigned();
            $table->foreign('berita_id')->references('id')->on('berita');
            $table->bigInteger('referral_id')->unsigned();
            $table->char('referral_entitas_kode',9)->default(0);
            $table->string('referral_telepon',20)->nullable();
            $table->string('referral_web_link',200)->nullable();
            $table->char('referral_status',1)->defaulr(0);
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
        Schema::dropIfExists('referral');
    }
}
