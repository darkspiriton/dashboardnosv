<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAuxclientTableAddIdFacebook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auxclients',function(Blueprint $table){
            $table->intger('facebook_id');
            $table->string('facebook_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auxclients',function(Blueprint $table){
            $table->dropColumn('facebook_id');
            $table->dropColumn('facebook_name');

        });
    }
}
