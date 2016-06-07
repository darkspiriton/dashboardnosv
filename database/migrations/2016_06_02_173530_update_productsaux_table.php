<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductsauxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auxproducts',function($table){
            $table->float('cost_provider');
            $table->float('utility');
        });

        Schema::table('auxmovements',function($table){
           $table->float('discount');
        });

        Schema::create('outfits',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->integer('cod');
            $table->float('price');
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::create('settlements',function(Blueprint $table){
            $table->increments('id');
            $table->float('price');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('auxproducts');
        });

        Schema::create('products_outfits',function(Blueprint $table){
           $table->integer('product_id')->unsigned();
           $table->integer('outfit_id')->unsigned();
           $table->foreign('product_id')->references('id')->on('auxproducts');
           $table->foreign('outfit_id')->references('id')->on('outfits');
        });

        Schema::create('aux_outfit_movements',function(Blueprint $table){
            $table->increments('id');
            $table->integer('outfit_id')->unsigned();
            $table->date('date_shipment');
            $table->string('situation',50)->nullable();
            $table->string('status');
            $table->timestamps();
            $table->foreign('outfit_id')->references('id')->on('outfits');
        });

        Schema::create('aux_outfit_movements_detail',function(Blueprint $table){
            $table->increments('id');
            $table->integer('outfit_movement_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('auxproducts');
            $table->foreign('outfit_movement_id')->references('id')->on('aux_outfit_movements');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auxproducts',function($table){
            $table->dropColumn('cost_provider');
            $table->dropColumn('utility');
        });

        Schema::table('auxmovements',function($table){
           $table->dropColumn('discount');
        });

        Schema::dropIfExists('outfits');
        Schema::dropIfExists('settlements');
        Schema::dropIfExists('products_outfits');
    }
}
