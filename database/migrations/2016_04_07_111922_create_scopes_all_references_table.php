<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScopesAllReferencesTable extends Migration
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
            $table->increments('id');
            $table->integer('user_id')->unsigned()->increments();
            $table->integer('channel_id')->unsigned()->increments();
            $table->integer('type_id')->unsigned()->increments();
            $table->string('observation');
            $table->timestamp('date');
        });

        // Scope Types
        Schema::create('types_scope', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
        });

        // Scope Details
        Schema::create('scope_details', function(Blueprint $table){
            $table->increments('id');
            $table->integer('scope_id')->unsigned()->increments();
            $table->integer('product_id')->unsigned()->increments();
        });

        /*
         *
            Relationships
         *
         */

        Schema::table('scopes', function(Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('channel_id')->references('id')->on('channels');
            $table->foreign('type_id')->references('id')->on('types_scope');
        });

        Schema::table('scope_details', function(Blueprint $table){
            $table->foreign('scope_id')->references('id')->on('scopes');
            $table->foreign('product_id')->references('id')->on('products');
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
