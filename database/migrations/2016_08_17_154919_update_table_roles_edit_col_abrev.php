<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableRolesEditColAbrev extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("roles", function(Blueprint $table){
            $table->string("abrev","10")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("roles", function(Blueprint $table){
            $table->string("abrev","3")->change();
        });
    }
}
