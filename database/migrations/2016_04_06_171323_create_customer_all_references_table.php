<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerAllReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Customers
        Schema::create('customers', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->integer('age');
            $table->integer('user_id')->unsigned()->increments();
            $table->timestamps();
        });

        // Ubigeos
        Schema::create('ubigeos', function(Blueprint $table){
            $table->string('UBIDEP',2);
            $table->string('UBIDEN',100);
            $table->string('UBIPRV',4);
            $table->string('UBIPRN',100);
            $table->string('UBIDST',6)->primary();
            $table->string('UBIDSN',100);
            $table->boolean('STATUS',1);
        });

        // Operators
        Schema::create('operators', function(Blueprint $table){
            $table->increments('id');
            $table->string('name', 100);
        });

        // Channels
        Schema::create('channels', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
        });

        // Addresses
        Schema::create('addresses', function(Blueprint $table){
            $table->increments('id');
            $table->integer('customer_id')->unsigned()->increments();
            $table->string('ubigeo_id',6);
            $table->string('description');
            $table->string('reference');
        });

        // Socials
        Schema::create('socials', function(Blueprint $table){
            $table->increments('id');
            $table->integer('customer_id')->unsigned()->increments();
            $table->integer('channel_id')->unsigned()->increments();
            $table->string('channel_url');
        });

        // Phones
        Schema::create('phones', function(Blueprint $table){
            $table->increments('id');
            $table->integer('customer_id')->unsigned()->increments();
            $table->integer('operator_id')->unsigned()->increments();
            $table->string('number', 50);
        });

        /*
         *
            Relationships
         *
         */

        Schema::table('customers', function(Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('addresses',function(Blueprint $table){
            $table->foreign('ubigeo_id')->references('UBIDST')->on('ubigeos');
            $table->foreign('customer_id')->references('id')->on('customers');
        });

        Schema::table('socials', function(Blueprint $table){
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('channel_id')->references('id')->on('channels');
        });

        Schema::table('phones',function(Blueprint $table){
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('operator_id')->references('id')->on('operators');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('ubigeos');
        Schema::dropIfExists('phones');
        Schema::dropIfExists('operators');
        Schema::dropIfExists('socials');
        Schema::dropIfExists('channels');
        Schema::dropIfExists('customers');
    }
}
