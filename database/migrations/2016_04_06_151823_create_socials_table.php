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
        Schema::create('socials', function(Blueprint $table){
            $table->increments('social_id');
            $table->integer('customer_id')->unsigned()->increments();
            $table->integer('channel_id')->unsigned()->increments();
            $table->string('channel_url');
        });

        Schema::table('socials', function(Blueprint $table){
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('channel_id')->references('channel_id')->on('channels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socials');
    }
}
