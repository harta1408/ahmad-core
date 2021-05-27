<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLembagaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lembaga', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lembaga_kode',50)->nullable();
            $table->string('lembaga_email', 100);
            $table->string('lembaga_phone',100)->nullable();
            $table->string('lembaga_nama',255)->nullable();
            $table->text('lembaga_alamat')->nullable();
            $table->text('lembaga_deskripsi')->nullable();            
            $table->timestamp('created_at');   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lembaga');
    }
}
