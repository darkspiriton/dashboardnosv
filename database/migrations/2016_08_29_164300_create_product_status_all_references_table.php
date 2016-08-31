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
        Schema::create("product_statuses", function(Blueprint $table){
            $table->increments("id");
            $table->string("name");
        });

        Schema::create("product_status_details", function(Blueprint $table){
            $table->increments("id");
            $table->string("description")->nullable();
            $table->integer("product_status_id")->unsigned();
            $table->timestamps();
            $table->foreign("product_status_id")->references("id")->on("product_statuses");
        });

        Schema::create("product_detail_statuses", function(Blueprint $table){
            $table->increments("id");
            $table->integer("product_id")->unsigned();
            $table->integer("status_id")->unsigned();
            $table->boolean("resolved");
            $table->timestamps();
            $table->foreign("product_id")->references("id")->on("auxproducts");
            $table->foreign("status_id")->references("id")->on("product_status_details");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("product_detail_statuses");
        Schema::dropIfExists("product_status_details");
        Schema::dropIfExists("product_statuses");
    }
}
