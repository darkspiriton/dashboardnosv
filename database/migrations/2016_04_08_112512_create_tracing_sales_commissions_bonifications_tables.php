<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTracingSalesCommissionsBonificationsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tracing
        Schema::create('tracing', function(Blueprint $table){
            $table->increments('id');
            $table->integer('shipment_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('observation');
            $table->timestamp('date');
        });

        // Sales
        Schema::create('sales', function(Blueprint $table){
            $table->increments('id');
            $table->integer('shipment_id')->unsigned();
            $table->float('total');
            $table->float('received');
            $table->float('change');
            $table->string('observation');
            $table->timestamp('date');
        });

        // Commissions
        Schema::create('commissions', function(Blueprint $table){
            $table->increments('id');
            $table->integer('sale_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('quantity');
            $table->timestamp('date');
        });

        // Bonifications
        Schema::create('bonifications', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->timestamp('date');
        });

        /*
        *
           Relationships
        *
        */

        Schema::table('tracing', function(Blueprint $table) {
            $table->foreign('shipment_id')->references('id')->on('shipments');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('sales', function(Blueprint $table) {
            $table->foreign('shipment_id')->references('id')->on('shipments');
        });

        Schema::table('commissions', function(Blueprint $table) {
            $table->foreign('sale_id')->references('id')->on('sales');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('bonifications', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonifications');
        Schema::dropIfExists('commissions');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('tracing');
    }
}
