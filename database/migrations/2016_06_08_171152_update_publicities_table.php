<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePublicitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publicities', function(Blueprint$table){
            $table->increments('id');
            $table->date('date');
            $table->integer('product_id')->unsigned();
            $table->boolean('status');

            $table->foreign('product_id')->references('id')->on('auxproducts');
        });

        Schema::create('types_processes',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('processes',function(Blueprint $table){
            $table->increments('id');
            $table->integer('publicity_id')->unsigned();
            $table->date('date');
            $table->integer('type_process_id')->unsigned();
            $table->boolean('status');

            $table->foreign('publicity_id')->references('id')->on('publicities');
            $table->foreign('type_process_id')->references('id')->on('types_processes');
        });

        Schema::create('types_socials',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('auxsocials',function(Blueprint $table){
            $table->increments('id');
            $table->date('date');
            $table->integer('publicity_id')->unsigned();
            $table->integer('type_social_id')->unsigned();

            $table->foreign('publicity_id')->references('id')->on('publicities');
            $table->foreign('type_social_id')->references('id')->on('types_socials');
        });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processes');
        Schema::dropIfExists('types_processes');
        Schema::dropIfExists('auxsocials');
        Schema::dropIfExists('types_socials');
        Schema::dropIfExists('publicities');
    }
}
