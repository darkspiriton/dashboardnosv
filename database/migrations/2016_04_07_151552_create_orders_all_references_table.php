<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersAllReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Orders
        Schema::create('orders', function(Blueprint $table){
            $table->increments('id');
            $table->integer('customer_id')->unsigned()->increments();
            $table->integer('user_id')->unsigned()->increments();
            $table->integer('status_id')->unsigned()->increments();
            $table->integer('interest_id')->nullable();
            $table->timestamps();
        });

        // Orders Detail
        Schema::create('order_details', function(Blueprint $table){
            $table->increments('id');
            $table->integer('order_id')->unsigned()->increments();
            $table->integer('product_id')->unsigned()->increments();
            $table->integer('quantity');
            $table->float('price');
        });

        // Orders status
        Schema::create('status_order', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
        });

        // calls
        Schema::create('calls', function(Blueprint $table){
            $table->increments('id');
            $table->integer('order_id')->unsigned()->increments();
            $table->integer('status_id')->unsigned()->increments();
            $table->string('observation');
            $table->date('date');
        });

        // Orders status
        Schema::create('status_call', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
        });

        /*
         *
            Relationships
         *
         */

        Schema::table('orders', function(Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('status_order');
        });

        Schema::table('calls', function(Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('status_id')->references('id')->on('status_call');
        });

        Schema::table('order_details', function(Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders');
//            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('calls');
        Schema::dropIfExists('status_call');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('status_order');

    }
}
