<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductStatusAllReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("product_status", function(Blueprint $table){
            $table->increments("id");
            $table->string("name");
        });

        Schema::create("product_status_detail", function(Blueprint $table){
            $table->increments("id");
            $table->string("description")->nullable();
            $table->integer("product_status_id")->unsigned();
            $table->timestamps();
            $table->foreign("product_status_id")->references("id")->on("product_status");
        });

        Schema::create("product_detail_status", function(Blueprint $table){
            $table->increments("id");
            $table->integer("product_id")->unsigned();
            $table->integer("status_id")->unsigned();
            $table->boolean("resolved");
            $table->timestamps();
            $table->foreign("product_id")->references("id")->on("auxproducts");
            $table->foreign("status_id")->references("id")->on("product_status_detail");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("product_detail_status");
        Schema::dropIfExists("product_status_detail");
        Schema::dropIfExists("product_status");
    }
}
