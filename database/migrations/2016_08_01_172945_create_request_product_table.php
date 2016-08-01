<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('requests_products',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->float('price');
            $table->boolean('status');        
            $table->integer('user_request_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_request_id')->references('id')->on('users_request');
        });

        Schema::create('photos_products',function(Blueprint $table){
            $table->increments('id');
            $table->string('url');
            $table->integer('request_product_id')->unsigned();

            $table->foreign('request_product_id')->references('id')->on('requests_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests_prodcuts');
        Schema::dropIfExists('photos_products');
    }
}
