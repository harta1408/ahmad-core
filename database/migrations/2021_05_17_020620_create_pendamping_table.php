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
            $table->string('pendamping_kode',50)->nullable();
            $table->string('pendamping_phone',100)->nullable();
            $table->string('pendamping_nama',255)->nullable();
            $table->longText('pendamping_alamat')->nullable();
            $table->string('pendamping_status_pegawai', 3, ['P', 'K'])->default('P');
            $table->string('pendamping_is_active',3, ['1', '0'])->default('1');
            $table->string('pendamping_status', 3, ['1', '0'])->default('1');
            $table->decimal('pendamping_honor', 18, 2)->default(0);
            $table->decimal('pendamping_komisi', 18, 2)->default(0);
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
        Schema::dropIfExists('Pendamping');
    }
}
