<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterestsAllReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Interests
        Schema::create('interests', function(Blueprint $table){
            $table->increments('id');
            $table->integer('status_id')->unsigned()->increments();
            $table->integer('channel_id')->unsigned()->increments();
            $table->integer('customer_id')->unsigned()->increments();
            $table->integer('user_id')->unsigned()->increments();
            $table->string('observation');
            $table->timestamp('date');
        });

        // Interest Details
        Schema::create('interest_details', function(Blueprint $table){
            $table->increments('id');
            $table->integer('interest_id')->unsigned()->increments();
            $table->integer('product_id')->unsigned()->increments();
        });

        // Interest Status
        Schema::create('status_interests',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
        });

        /*
         *
            Relationships
         *
         */

        Schema::table('interests', function(Blueprint $table){
            $table->foreign('status_id')->references('id')->on('status_interests');
            $table->foreign('channel_id')->references('id')->on('channels');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('interest_details', function(Blueprint $table){
            $table->foreign('interest_id')->references('id')->on('interests');
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
        Schema::dropIfExists('interest_details');
        Schema::dropIfExists('interests');
        Schema::dropIfExists('status_interests');
    }
}
