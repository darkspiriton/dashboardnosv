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
            $table->increments('customer_id');
            $table->string('name');
            $table->integer('age');
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
            $table->increments('operator_id');
            $table->string('operator_name', 100);
        });

        // Channels
        Schema::create('channels', function(Blueprint $table){
            $table->increments('channel_id');
            $table->string('channel_name');
        });

        // Addresses
        Schema::create('addresses', function(Blueprint $table){
            $table->increments('address_id');
            $table->integer('customer_id')->unsigned()->increments();
            $table->string('ubigeo_id',6);
            $table->string('description');
            $table->string('reference');
        });

        Schema::table('addresses',function(Blueprint $table){
            $table->foreign('ubigeo_id')->references('UBIDST')->on('ubigeos');
            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });

        // Socials
        Schema::create('socials', function(Blueprint $table){
            $table->increments('social_id');
            $table->integer('customer_id')->unsigned()->increments();
            $table->integer('channel_id')->unsigned()->increments();
            $table->string('channel_url');
        });

        Schema::table('socials', function(Blueprint $table){
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('channel_id')->references('channel_id')->on('channels');
        });

        // Phones
        Schema::create('phones', function(Blueprint $table){
            $table->increments('phone_id');
            $table->integer('customer_id')->unsigned()->increments();
            $table->integer('operator_id')->unsigned()->increments();
            $table->string('phone_number', 50);
        });

        Schema::table('phones',function(Blueprint $table){
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('operator_id')->references('operator_id')->on('operators');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
        Schema::dropIfExists('ubigeos');
        Schema::dropIfExists('operators');
        Schema::dropIfExists('channels');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('socials');
        Schema::dropIfExists('phones');
    }
}
