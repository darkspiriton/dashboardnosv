<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsWithReferenceTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("notification_types", function(Blueprint $table){
            $table->increments("id");
            $table->string("name");
        });

        Schema::create("notifications", function(Blueprint $table){
            $table->increments("id");
            $table->string("title");
            $table->string("body")->nullable();
            $table->string("event")->nullable();
            $table->integer("type_id")->unsigned();
            $table->boolean("status");
            $table->timestamps();
            $table->foreign("type_id")->references("id")->on("notification_types");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("notifications");
        Schema::dropIfExists("notification_types");
    }
}
