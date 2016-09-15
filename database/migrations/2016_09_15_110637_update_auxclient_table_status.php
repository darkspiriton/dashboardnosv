<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAuxclientTableStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_status',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
        });

        Schema::table('auxclients',function(Blueprint $table){
            $table->integer('status_id')->unsigned()->nullable();
            $table->string('address')->nullable();
            $table->string('reference')->nullable();
            $table->foreign('status_id')->references('id')->on('auxclients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients_status');
        Schema::table('auxclients',function(Blueprint $table){
            $table->dropColumn('status_id');
        });
    }
}
