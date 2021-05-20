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
            $table->string('produk_deskripsi',200)->nullable();
            $table->string('produk_photo',100)->nullable();
            $table->double('produk_harga',12,2)->default(0);
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
