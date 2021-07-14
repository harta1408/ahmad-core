<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHadiahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hadiah', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hadiah_nama',50)->nullable();
            $table->char('hadiah_jenis',1)->default(1);
            $table->string('hadiah_no_seri',30)->nullable();
            $table->double('hadiah_nilai',12,2)->default(0);
            $table->double('hadiah_nominal',12,2)->default(0);
            $table->dateTime('hadiah_mulai')->nullable();
            $table->dateTime('hadiah_akhir')->nullable();
            $table->char('hadiah_status',1)->default(0);
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
        Schema::dropIfExists('hadiah');
    }
}
