<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKirimProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kirim_produk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('produk_id')->unsigned();
            $table->foreign('produk_id')->references('id')->on('produk');
            $table->bigInteger('santri_id')->unsigned();
            $table->foreign('santri_id')->references('id')->on('santri');
            $table->bigInteger('donatur_id')->unsigned();
            $table->foreign('donatur_id')->references('id')->on('donatur');
            $table->bigInteger('donasi_id')->unsigned();
            $table->foreign('donasi_id')->references('id')->on('donasi');
            $table->string('kirim_produk_no_seri',100)->nullable();
            $table->string('kirim_nama',30)->nullable();
            $table->string('kirim_telepon',20)->nullable();
            $table->string('kirim_penerima_nama',30)->nullable();
            $table->string('kirim_penerima_telepon',20)->nullable();
            $table->string('kirim_penerima_alamat')->nullable();
            $table->string('kirim_penerima_kode_pos',10)->nullable();
            $table->string('kirim_penerima_kelurahan',50)->nullable();
            $table->string('kirim_penerima_kota',50)->nullable();
            $table->string('kirim_penerima_kecamatan',50)->nullable();
            $table->string('kirim_penerima_provinsi',50)->nullable();
            $table->string('kirim_no_resi',50)->nullable();
            $table->double('kirim_biaya',12,2)->default(0); 
            $table->string('produk_serial_no')->nullable();
            $table->date('kirim_tanggal_kirim')->nullable();
            $table->date('kirim_tanggal_terima')->nullable();
            $table->char('kirim_status',1)->default(0);
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
        Schema::dropIfExists('kirim_produk');
    }
}
