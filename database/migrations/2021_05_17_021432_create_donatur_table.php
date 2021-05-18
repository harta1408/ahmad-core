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
            $table->string('donatur_ktp',20)->nullable();
            $table->string('donatur_nama',50)->nullable();
            $table->string('donatur_mobile_no',20)->nullable();
            $table->string('donatur_email',100)->unique();
            $table->string('donatur_photo',100)->nullable();
            $table->string('donatur_kerja',100)->nullable();
            $table->string('donatur_alamat',100)->nullable();
            $table->char('donatur_rangkap',1)->default('0');
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
