<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('scopes',function(Blueprint $table){
//            $table->increments('id');
//            $table->integer('social_id')->unsigned();
//            $table->string('name');
//            $table->timestamps();
//
//            $table->foreign('social_id')->references('id')->on('socials');
//        });
//
//        Schema::create('comments',function(Blueprint $table){
//            $table->increments('id');
//            $table->integer('social_id')->unsigned();
//            $table->integer('cant');
//            $table->timestamps();
//
//            $table->foreign('social_id')->references('id')->on('socials');
//        });
//
//        Schema::create('likes',function(Blueprint $table){
//            $table->increments('id');
//            $table->integer('social_id')->unsigned();
//            $table->integer('cant');
//
//            $table->foreign('social_id')->references('id')->on('socials');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('scopes');
//        Schema::dropIfExists('comments');
//        Schema::dropIfExists('likes');
    }
}
