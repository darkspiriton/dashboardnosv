<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_requests',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->integer('phone');
            $table->timestamps();
        });

        Schema::create('requests_applications',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->integer('user_request_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_request_id')->references('id')->on('users_requests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_request');
        Schema::dropIfExists('requests_applications');
    }
}
