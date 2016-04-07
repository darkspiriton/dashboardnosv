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
            $table->increments('order_id');
            $table->integer('customer_id')->unsigned()->increments();
            $table->integer('user_id')->unsigned()->increments();
            $table->integer('status_id')->unsigned()->increments();
            $table->integer('interest_id')->nullable();
            $table->timestamps();
        });

        // Orders Detail
        Schema::create('order_details', function(Blueprint $table){
            $table->increments('detail_id');
            $table->integer('order_id')->unsigned()->increments();
            $table->integer('product_id')->unsigned()->increments();
            $table->integer('quantity');
            $table->float('price');
        });

        // Orders status
        Schema::create('status_order', function(Blueprint $table){
            $table->increments('status_id');
            $table->string('status_name');
        });

        // calls
        Schema::create('calls', function(Blueprint $table){
            $table->increments('call_id');
            $table->integer('order_id')->unsigned()->increments();
            $table->integer('status_id')->unsigned()->increments();
            $table->string('observation');
            $table->date('date');
        });

        // Orders status
        Schema::create('status_call', function(Blueprint $table){
            $table->increments('status_id');
            $table->string('status_name');
        });

        //RelationShips
        Schema::table('orders', function(Blueprint $table) {
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('status_id')->references('status_id')->on('status_order');
        });

        Schema::table('calls', function(Blueprint $table) {
            $table->foreign('order_id')->references('order_id')->on('orders');
            $table->foreign('status_id')->references('status_id')->on('status_call');
        });

        Schema::table('order_details', function(Blueprint $table) {
            $table->foreign('order_id')->references('order_id')->on('orders');
//            $table->foreign('product_id')->references('product_id')->on('products');
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
        Schema::dropIfExists('orders');
        Schema::dropIfExists('status_order');
        Schema::dropIfExists('calls');
        Schema::dropIfExists('status_call');
    }
}
