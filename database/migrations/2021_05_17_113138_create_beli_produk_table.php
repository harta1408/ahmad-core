<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeliProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beli_produk', function (Blueprint $table) {
            $table->bigInteger('beli_id')->unsigned();
            $table->foreign('beli_id')->references('id')->on('beli'); 
            $table->integer('produk_id')->unsigned();
            $table->foreign('produk_id')->references('id')->on('produk'); 
            $table->integer('beli_produk_jml')->default(0);
            $table->double('beli_produk_harga',12,2)->default(0);
            $table->double('beli_produk_total',12,2)->default(0);
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
        Schema::dropIfExists('beli_produk');
    }
}
