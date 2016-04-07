<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistersScopeAllReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Scopes
        Schema::create('scopes', function(Blueprint $table){
            $table->increments('scope_id');
            $table->integer('user_id')->unsigned()->increments();
            $table->integer('channel_id')->unsigned()->increments();
            $table->integer('type_id')->unsigned()->increments();
            $table->string('observation');
        });

        // Scope Types
        Schema::create('types_scope', function(Blueprint $table){
            $table->increments('type_id');
            $table->string('type_name');
        });

        // Scope Details
        Schema::create('scope_details', function(Blueprint $table){
            $table->increments('detail_id');
            $table->integer('scope_id')->unsigned()->increments();
            $table->integer('product_id')->unsigned()->increments();
        });

        // Relationships
        Schema::table('scopes', function(Blueprint $table){
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('channel_id')->references('channel_id')->on('channels');
            $table->foreign('type_id')->references('type_id')->on('types_scope');
        });

        Schema::table('scope_details', function(Blueprint $table){
            $table->foreign('scope_id')->references('scope_id')->on('scopes');
//            $table->foreign('product_id')->references('product_id')->on('products');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scope_details');
        Schema::dropIfExists('scopes');
        Schema::dropIfExists('types_scope');
    }
}
