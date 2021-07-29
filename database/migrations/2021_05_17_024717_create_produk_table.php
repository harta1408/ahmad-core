<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->increments('id');
            $table->string('produk_nama',100)->nullable();
            $table->text('produk_deskripsi')->nullable();
            $table->string('produk_lokasi_gambar')->nullable();
            $table->string('produk_lokasi_video')->nullable();
            $table->integer('produk_masa_bimbingan',3)->default(0);
            $table->double('produk_harga',12,2)->default(0);
            $table->double('produk_discount',6,2)->default(0);
            $table->integer('produk_berat')->default(0);
            $table->integer('produk_stok')->default(0);
            $table->char('produk_status',1)->default(0);
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
        Schema::dropIfExists('produk');
    }
}
