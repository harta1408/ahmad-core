<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendampingKampanyeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendamping_kampanye', function (Blueprint $table) {
            $table->bigInteger('pendamping_id')->unsigned();
            $table->foreign('pendamping_id')->references('id')->on('pendamping');
            $table->integer('kampanye_id')->unsigned();
            $table->foreign('kampanye_id')->references('id')->on('kampanye');
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
        Schema::dropIfExists('pendamping_kampanye');
    }
}
