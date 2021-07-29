<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKirimManifestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kirim_manifest', function (Blueprint $table) {
            $table->bigInteger('kirim_produk_id')->unsigned();
            $table->foreign('kirim_produk_id')->references('id')->on('kirim_produk');
            $table->string('kirim_manifest_code',10)->nullable();
            $table->string('kirim_manifest_no_resi',50)->nullable();
            $table->string('kirim_manifest_kurir',100)->nullable();
            $table->date('kirim_manifest_tanggal')->nullable();
            $table->time('kirim_manifest_waktu')->nullable();
            $table->string('kirim_manifest_deskripsi')->nullable(); 
            $table->string('kirim_manifest_kota')->nullable(); 
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
        Schema::dropIfExists('kirim_manifest');
    }
}
