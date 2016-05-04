<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsauxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alarms', function(Blueprint $table){
            $table->increments('id');
            $table->integer('day');
            $table->integer('count');
            $table->timestamps();
        });

        Schema::create('sizes', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('colors', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('providers', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('auxproducts', function(Blueprint $table){
            $table->increments('id');
            $table->integer('cod')->unique();
            $table->integer('provider_id')->unsigned();
            $table->integer('color_id')->unsigned();
            $table->integer('size_id')->unsigned();
            $table->integer('alarm_id')->unsigned();
            $table->string('name');
            $table->boolean('status');
            $table->timestamps();
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->foreign('color_id')->references('id')->on('colors');
            $table->foreign('size_id')->references('id')->on('sizes');
            $table->foreign('alarm_id')->references('id')->on('alarms');
        });

        Schema::create('auxmovements', function(Blueprint $table){
            $table->increments('id');
            $table->integer('product_id')->unsigned()->increments();
            $table->date('date');
            $table->date('date_shipment');
            $table->string('situation',12);
            $table->string('status');
            $table->timestamps();
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
       
        Schema::dropIfExists('auxmovements');
        Schema::dropIfExists('auxproducts');
        Schema::dropIfExists('alarms');
        Schema::dropIfExists('sizes');
        Schema::dropIfExists('colors');
        Schema::dropIfExists('providers');
    }
}
