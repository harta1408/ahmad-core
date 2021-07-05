<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMutasiRekeningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutasi_rekening', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('date')->nullable();
            $table->dateTime('moota_date')->nullable();
            $table->string('description')->nullable();
            $table->double('amount',18,2)->default(0);
            $table->char('type',2)->default('DB');
            $table->char('mutation_id',2)->default('XX');
            $table->string('note',100)->nullable();
            $table->char('status',1)->default(0);
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
        Schema::dropIfExists('mutasi_rekening');
    }
}
