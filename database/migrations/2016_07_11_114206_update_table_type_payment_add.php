<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableTypePaymentAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types_payments_providers',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
        });

        Schema::table('payments_providers',function(Blueprint $table){
            $table->integer('type_payment_id')->unsigned()->nullable();
            $table->foreign('type_payment_id')->references('id')->on('types_payments_providers');
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
            $table->dropColumn('type_payment_id');
        });
        Schema::dropIfExists('types_payments_providers');
    }
}
