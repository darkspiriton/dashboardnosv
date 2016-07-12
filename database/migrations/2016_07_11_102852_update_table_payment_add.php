<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTablePaymentAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks',function(Blueprint $table){
           $table->increments('id');
           $table->string('name');

        });

        Schema::table('payments_providers', function(Blueprint $table){
           $table->integer('bank_id')->unsigned()->nullable();
           $table->foreign('bank_id')->references('id')->on('banks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments_providers',function(Blueprint $table){
            $table->dropColumn('bank_id');
        });
        Schema::dropIfExists('banks');
        
    }
}
