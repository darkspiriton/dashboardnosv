<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAuxproductAndOutfitAndDiscountTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outfits',function(Blueprint $table){
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::create('aux_outfit_movements',function(Blueprint $table){
            $table->increments('id');
            $table->integer('outfit_id')->unsigned();
            $table->date('date_shipment');
            $table->string('situation',50)->nullable();
            $table->string('status');
            $table->boolean('respond')->default(0);
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
        Schema::table('outfits',function(Blueprint $table){
            $table->dropColumn('status');
            $table->dropTimestamps();
        });

        Schema::dropIfExists('aux_outfit_movements');
        Schema::dropIfExists('aux_outfit_movements_detail');
    }
}
