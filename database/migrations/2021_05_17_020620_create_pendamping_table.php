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
            $table->string('pendamping_gender',10)->default('PRIA');
            $table->text('pendamping_alamat')->nullable();
            $table->string('pendamping_kode_pos',10)->nullable();
            $table->string('pendamping_kelurahan',50)->nullable();
            $table->string('pendamping_kota',50)->nullable();
            $table->string('pendamping_kecamatan',50)->nullable();
            $table->string('pendamping_provinsi',50)->nullable();
            $table->string('pendamping_status_pegawai', 10, ['PERMANEN', 'KONTRAK'])->default('PERMANEN');
            $table->decimal('pendamping_honor', 18, 2)->default(0);
            $table->decimal('pendamping_komisi', 18, 2)->default(0);
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
