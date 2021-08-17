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
            $table->string('lembaga_id',5)->primary();
            $table->string('lembaga_nama',100)->nullable();
            $table->string('lembaga_email', 100);
            $table->string('lembaga_telepon',100)->nullable();
            $table->string('lembaga_alamat',100)->nullable();
            $table->string('lembaga_tentang_ahmad_judul',50)->nullable(); 
            $table->string('lembaga_kota',100)->nullable();
            $table->string('lembaga_provinsi',100)->nullable(); 
            $table->integer('lembaga_kota_id')->deafult(0);
            $table->integer('lembaga_provinsi_id')->default(0); 
            $table->text('lembaga_tentang_ahmad_isi')->nullable();   
            $table->string('lembaga_landing_donatur_judul',50)->nullable(); 
            $table->text('lembaga_landing_donatur_isi')->nullable();
            $table->string('lembaga_landing_santri_judul',50)->nullable(); 
            $table->text('lembaga_landing_santri_isi')->nullable();
            $table->string('lembaga_landing_pendamping_judul',50)->nullable(); 
            $table->text('lembaga_landing_pendamping_isi')->nullable();
            $table->string('lembaga_landing_mitra_judul',50)->nullable(); 
            $table->text('lembaga_landing_mitra_isi')->nullable();
            $table->string('lembaga_landing_produk_judul',50)->nullable(); 
            $table->text('lembaga_landing_produk_isi')->nullable();  
            $table->integer('lembaga_adjust_hijr',1)->default(0);     
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
        Schema::dropIfExists('lembaga');
    }
}
