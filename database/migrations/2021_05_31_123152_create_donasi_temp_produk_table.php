<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonasiTempProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donasi_temp_produk', function (Blueprint $table) {
            $table->string('temp_donasi_no',6)->nullable();
            $table->integer('produk_id')->default(0);
            $table->integer('temp_donasi_produk_jml')->default(0);
            $table->double('temp_donasi_produk_harga',12,2)->default(0);
            $table->double('temp_donasi_produk_total',16,2)->default(0);
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
        Schema::dropIfExists('donasi_temp_produk');
    }
}
