<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKampanyeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kampanye', function (Blueprint $table) {
            $table->increments('id');
            $table->text('kampanye_isi')->nullable();
            $table->char('kampanye_jenis',1)->default(0);
            $table->text('kampanye_lokasi_gambar')->nullable();
            $table->text('kampanye_lokasi_video')->nullable();
            $table->char('kampanye_status',1)->default(0);            
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
        Schema::dropIfExists('kampanye');
    }
}
