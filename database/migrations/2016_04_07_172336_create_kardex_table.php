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
        Schema::create('kardexs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_cod')->unique();
            $table->integer('product_id')->unsigned();
            $table->boolean('stock');
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products');
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
            $table->date('date');
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
        Schema::dropIfExists('movements');
        Schema::dropIfExists('types_movements');
        Schema::dropIfExists('kardexs');

    }
}
