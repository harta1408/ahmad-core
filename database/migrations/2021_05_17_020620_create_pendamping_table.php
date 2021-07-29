<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendampingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendamping', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pendamping_email',100)->unique();   
            $table->string('pendamping_nama',100)->nullable();
            $table->string('pendamping_kode',9)->nullable();
            $table->string('pendamping_nid',20)->nullable();
            $table->string('pendamping_telepon',100)->nullable();
            $table->string('pendamping_tmp_lahir',50)->nullable();
            $table->date('pendamping_tgl_lahir')->nullable();
            $table->string('pendamping_gender',10)->default('PRIA'); 
            $table->string('pendamping_agama',10)->default('ISLAM');
            $table->text('pendamping_alamat')->nullable();
            $table->string('pendamping_kerja',100)->nullable();
            $table->string('pendamping_kode_pos',10)->nullable();
            $table->string('pendamping_kelurahan',50)->nullable();
            $table->integer('pendamping_kecamatan_id')->default(0);
            $table->string('pendamping_kecamatan',50)->nullable();
            $table->integer('pendamping_kota_id')->default(0);
            $table->string('pendamping_kota',50)->nullable();
            $table->string('pendamping_provinsi',50)->nullable();
            $table->integer('pendamping_provinsi_id')->default(0);
            $table->text('pendamping_lokasi_photo')->nullable();
            $table->string('pendamping_status_pegawai', 10, ['TETAP', 'KONTRAK'])->default('TETAP');
            $table->double('pendamping_honor', 18, 2)->default(0);
            $table->double('pendamping_komisi', 18, 2)->default(0);
            $table->char('pendamping_rangkap',1)->default('0');
            $table->char('pendamping_min_referral',1)->default(0);
            $table->char('pendamping_status', 1, ['1', '0'])->default('1');
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
        Schema::dropIfExists('Pendamping');
    }
}
