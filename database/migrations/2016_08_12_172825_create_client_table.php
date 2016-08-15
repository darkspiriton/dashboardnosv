<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auxclients',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('dni');
            $table->string('address');
            $table->string('reference');
        });

        Schema::table('auxmovements',function(Blueprint $table){
            $table->integer('client_id')->unsigned()->nullable();

            $table->foreign('client_id')->references('id')->on('auxclients');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auxclients');
        Schema::table('auxmovements',function(Blueprint $table){
            $table->dropColumn('client_id');
        });
    }
}
