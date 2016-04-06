<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
        Schema::dropIfExists('phones');
    }
}
