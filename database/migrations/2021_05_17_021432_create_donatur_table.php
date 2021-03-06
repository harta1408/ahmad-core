<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonaturTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donatur', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('donatur_kode',9)->nullable();
            $table->string('donatur_nid',20)->nullable(); 
            $table->string('donatur_email',100)->unique();
            $table->string('donatur_nama',50)->nullable();
            $table->string('donatur_tmp_lahir',50)->nullable();
            $table->date('donatur_tgl_lahir')->nullable();
            $table->string('donatur_gender',10)->default('PRIA');
            $table->string('donatur_agama',10)->default('ISLAM');
            $table->string('donatur_telepon',20)->nullable();
            $table->string('donatur_kerja',100)->nullable();
            $table->text('donatur_lokasi_photo')->nullable();
            $table->text('donatur_alamat')->nullable();
            $table->string('donatur_kode_pos',10)->nullable();
            $table->string('donatur_kelurahan',50)->nullable();
            $table->integer('donatur_kecamatan_id')->default(0);
            $table->string('donatur_kecamatan',50)->nullable();
            $table->integer('donatur_kota_id')->default(0);
            $table->string('donatur_kota',50)->nullable();
            $table->integer('donatur_provinsi_id')->default(0);
            $table->string('donatur_provinsi',50)->nullable();
            $table->char('donatur_rangkap',1)->default('0');
            $table->char('donatur_min_referral',1)->default(0);
            $table->char('donatur_hide',1)->default();
            $table->char('donatur_status',1)->default('0');
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
        Schema::dropIfExists('donatur');
    }
}
