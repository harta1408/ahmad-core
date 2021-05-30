<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeritaDonaturTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berita_donatur', function (Blueprint $table) {
            $table->bigInteger('berita_id')->unsigned();
            $table->foreign('berita_id')->references('id')->on('berita');
            $table->bigInteger('donatur_id')->unsigned();
            $table->foreign('donatur_id')->references('id')->on('donatur');
            $table->text('referral_web_link')->nullable();
            $table->char('referral_status',1)->default(0);
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
        Schema::dropIfExists('berita_donatur');
    }
}
