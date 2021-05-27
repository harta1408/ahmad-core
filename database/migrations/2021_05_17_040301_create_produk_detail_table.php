<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdukDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('produk_id')->unsigned();
            $table->foreign('produk_id')->references('id')->on('produk');
            $table->string('produk_detail_nama')->nullable();
            $table->integer('produk_detail_jml')->default(0);
            $table->double('produk_detail_harga',12,2)->default(0);
            $table->char('produk_detail_status',1)->default(0);
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
        Schema::dropIfExists('produk_detail');
    }
}
