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
        Schema::create('products', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('price');
            $table->string('product_code');
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::create('types_attributes',function(Blueprint $table){
            $table->increments('id');
            $table->string('name',100);
            $table->timestamps();
        });

        Schema::create('attributes',function(Blueprint $table){
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->string('valor',100);
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->ondelete('cascade');
            $table->foreign('type_id')->references('id')->on('types_attributes');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('types_attributes');
        Schema::dropIfExists('products');

    }
}
