<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonasiProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donasi_produk', function (Blueprint $table) {
            $table->bigInteger('donasi_id')->unsigned();
            $table->foreign('donasi_id')->references('id')->on('donasi'); 
            $table->integer('produk_id')->unsigned();
            $table->foreign('produk_id')->references('id')->on('produk'); 
            $table->integer('donasi_produk_jml')->default(0);
            $table->double('donasi_produk_harga',12,2)->default(0);
            $table->double('donasi_produk_total',16,2)->default(0);
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
        Schema::dropIfExists('donasi_produk');
    }
}
