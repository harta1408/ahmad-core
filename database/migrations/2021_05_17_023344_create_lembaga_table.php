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
            $table->bigIncrements('id');
            $table->string('lembaga_kode',50)->primary();
            $table->string('lembaga_email', 100);
            $table->string('lembaga_phone',100)->nullable();
            $table->string('lembaga_nama',255);
            $table->longText('lembaga_alamat');
            $table->longText('lembaga_deskripsi');            
            $table->index('lembaga_kode');
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
