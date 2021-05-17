<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembinaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Pembina', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Pembina_email',100)->unique();   
            $table->string('pembina_kode',50)->nullable();
            $table->string('Pembina_phone',100)->nullable();
            $table->string('Pembina_nama',255)->nullable();
            $table->longText('Pembina_alamat')->nullable();
            $table->string('Pembina_status_pegawai', 3, ['P', 'K'])->default('P');
            $table->string('Pembina_is_active',3, ['1', '0'])->default('1');
            $table->string('Pembina_status', 3, ['1', '0'])->default('1');
            $table->decimal('Pembina_honor', 18, 2)->default(0);
            $table->decimal('Pembina_komisi', 18, 2)->default(0);
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
        Schema::dropIfExists('pembina');
    }
}
