<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMitraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mitra', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mitra_email',100)->unique();   
            $table->string('mitra_nama_usaha',100)->nullable();
            $table->string('mitra_npwp',30)->nullable();
            $table->string('mitra_jenis_usaha',50)->nullable();
            $table->string('mitra_deskripsi_usaha')->nullable();
            $table->string('mitra_pic',100)->nullable();
            $table->string('mitra_pic_telepon',100)->nullable();
            $table->text('mitra_alamat')->nullable();
            $table->char('mitra_status', 1)->default('0');
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
        Schema::dropIfExists('mitra');
    }
}
