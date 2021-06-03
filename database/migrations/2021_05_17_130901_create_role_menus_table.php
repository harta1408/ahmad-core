<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('role_id')->unsigned();       
            $table->bigInteger('menu_id')->unsigned(); 
            $table->foreign('role_id')
                    ->references('id')->on('roles')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');       
            $table->foreign('menu_id')
                    ->references('id')->on('menus')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');        
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
        Schema::dropIfExists('role_menus');
    }
}
