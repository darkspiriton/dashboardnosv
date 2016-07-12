<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types_discounts_providers',function(Blueprint $table){
            $table->increments('id');
            $table->string('type');
        });

        Schema::create('payments_providers',function(Blueprint $table){
            $table->increments('id');
            $table->integer('provider_id')->unsigned();
            $table->integer('type_discount_id')->unsigned();            
            $table->string('name_bank');
            $table->dateTime('date');
            $table->float('amount');
            $table->string('reason');
            $table->float('amount_discount');

            $table->foreign('type_discount_id')->references('id')->on('types_discounts_providers');
            $table->foreign('provider_id')->references('id')->on('providers');
        });

        Schema::create('payments_details',function(Blueprint $table){
           $table->increments('id');
           $table->integer('payment_id')->unsigned();
           $table->integer('product_id')->unsigned();

           $table->foreign('payment_id')->references('id')->on('payments_providers');
           $table->foreign('product_id')->references('id')->on('auxproducts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('types_discounts_providers');
        Schema::dropIfExists('payments_providers');
        Schema::dropIfExists('payments_details');
    }
}
