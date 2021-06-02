<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSantriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('santri', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('santri_kode',9)->nullable();
            $table->string('santri_nid',20)->nullable(); 
            $table->string('santri_email',100)->unique();
            $table->string('santri_nama',50)->nullable();
            $table->string('santri_tmp_lahir',50)->nullable();
            $table->date('santri_tgl_lahir')->nullable();
            $table->string('santri_gender',10)->default('PRIA');
            $table->string('santri_agama',10)->default('ISLAM');
            $table->string('santri_telepon',20)->nullable();
            $table->string('santri_kerja',100)->nullable();
            $table->text('santri_lokasi_photo')->nullable();
            $table->text('santri_alamat')->nullable();
            $table->string('santri_kode_pos',10)->nullable();
            $table->string('santri_kelurahan',50)->nullable();
            $table->string('santri_kota',50)->nullable();
            $table->string('santri_kecamatan',50)->nullable();
            $table->string('santri_provinsi',50)->nullable();
            $table->char('santri_rangkap',1)->default('0');
            $table->char('santri_min_referral',1)->default(0);
            $table->char('santri_status',1)->default('0');
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
        Schema::dropIfExists('santri');
    }
}
