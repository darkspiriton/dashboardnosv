<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrestashopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_prestashop',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
        });

        Schema::create('requests_prestashop',function(Blueprint $table){
            $table->increments('id');                        
            $table->boolean('status');
            $table->float('total_price');
            $table->integer('user_id')->unsigned();            
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users_prestashop');
        });

        Schema:: create('products_prestahop', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('url_image');
            $table->string('url_product');
            $table->integer('stock');
            $table->float('price');
            $table->integer('cant');
            $table->integer('request_id')->unsigned();

            $table->foreign('request_id')->references('id')->on('products_prestahop');
        });




    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_prestashop');
        Schema::dropIfExists('requests_prestashop');
        Schema::dropIfExists('products_prestahop');
    }
}
