<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
