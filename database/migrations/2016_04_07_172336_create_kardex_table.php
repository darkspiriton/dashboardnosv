<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKardexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups_attributes',function(Blueprint $table){
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::create('kardexs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('group_attribute_id')->unsigned();
            $table->boolean('stock');
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('group_attribute_id')->references('id')->on('groups_attributes');
        });

        Schema::create('types_attributes',function(Blueprint $table){
            $table->increments('id');
            $table->string('name',100);
            $table->timestamps();
        });

        Schema::create('attributes',function(Blueprint $table){
            $table->increments('id');
            $table->integer('type_id')->unsigned();
            $table->string('valor',100);
            $table->timestamps();
            $table->foreign('type_id')->references('id')->on('types_attributes');
        });

        Schema::create('attributes_kardexs',function(Blueprint $table){
            $table->increments('id');
            $table->integer('group_attribute_id')->unsigned();
            $table->integer('attribute_id')->unsigned();
            $table->timestamps();
            $table->foreign('group_attribute_id')->references('id')->on('groups_attributes');
            $table->foreign('attribute_id')->references('id')->on('attributes');
        });

        Schema::create('types_movements',function(Blueprint $table){
            $table->increments('id');
            $table->string('name',100);
        });

        Schema::create('movements',function (Blueprint $table){
            $table->increments('id');
            $table->integer('ship_id')->unsigned();
            $table->integer('kardex_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->timestamp('date');
            $table->foreign('kardex_id')->references('id')->on('kardexs');
            $table->foreign('type_id')->references('id')->on('types_movements');
            $table->foreign('ship_id')->references('id')->on('shipments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attributes_kardexs');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('types_attributes');
        Schema::dropIfExists('movements');
        Schema::dropIfExists('types_movements');
        Schema::dropIfExists('kardexs');
        Schema::dropIfExists('groups_attributes');

    }
}
