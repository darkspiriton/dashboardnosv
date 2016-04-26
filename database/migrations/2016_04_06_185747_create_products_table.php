<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types_products', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->timestamps();

        });



        Schema::create('products', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('price');
            $table->string('image');
            $table->string('product_code',12);
            $table->boolean('status');
            $table->integer('type_product_id')->unsigned()->increments();
            $table->timestamps();
            $table->foreign('type_product_id')->references('id')->on('types_products');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('products');
        Schema::dropIfExists('types_products');

    }
}
