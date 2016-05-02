<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllForeignKeyRelationships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
            $table->foreign('kardex_id')->references('id')->on('kardexs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
