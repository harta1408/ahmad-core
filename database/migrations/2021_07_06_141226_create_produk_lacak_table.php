<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdukLacakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk_lacak', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_resi',50)->nullable();
            $table->string('kurir',100)->nullable();
            $table->dateTime('tanggal')->nullable();
            $table->string('deskripsi')->nullable(); 
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
        Schema::dropIfExists('produk_lacak');
    }
}
