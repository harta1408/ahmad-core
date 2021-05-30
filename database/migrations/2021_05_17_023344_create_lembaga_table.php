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
            $table->increments('id');
            $table->string('lembaga_kode',50)->nullable();
            $table->string('lembaga_email', 100);
            $table->string('lembaga_phone',100)->nullable();
            $table->string('lembaga_nama',100)->nullable();
            $table->string('lembaga_alamat',100)->nullable();
            $table->text('lembaga_deskripsi')->nullable();    
            $table->string('lembaga_main_judul',100)->nullable(); 
            $table->text('lembaga_main_konten')->nullable();
            $table->string('lembaga_main_lokasi_gambar',100)->nullable(); 
            $table->string('lembaga_donatur_judul',100)->nullable(); 
            $table->text('lembaga_donatur_konten')->nullable();    
            $table->string('lambaga_santri_judul',100)->nullable();    
            $table->text('lembaha_santri_konten')->nullable();
            $table->string('lambaga_pendamping_judul',100)->nullable(); 
            $table->text('lembaha_pendamping_konten')->nullable();    
            $table->string('lambaga_produk_judul',100)->nullable();  
            $table->text('lembaha_produk_konten')->nullable();
            $table->string('lambaga_mitra_judul',100)->nullable(); 
            $table->text('lembaha_mitra_konten')->nullable();        
            $table->timestamp('created_at');   
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
