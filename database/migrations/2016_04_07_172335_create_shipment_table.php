<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments_status',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('types_shipments', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('routes_sheets',function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->timestamp('date');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->integer('address_id')->unsigned();
            $table->integer('shipment_status_id')->unsigned();
            $table->integer('type_ship_id')->unsigned();
            $table->timestamp('date');
            $table->float('cost');
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->foreign('shipment_status_id')->references('id')->on('shipments_status');
        });

        Schema::create('routes_sheets_shipments', function(Blueprint $table){
            $table->increments('id');
            $table->integer('route_sheet_id')->unsigned();
            $table->integer('shipment_id')->unsigned();
            $table->string('observations');
            $table->foreign('route_sheet_id')->references('id')->on('routes_sheets');
            $table->foreign('shipment_id')->references('id')->on('shipments');

        });




    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes_sheets_shipments');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('routes_sheets');
        Schema::dropIfExists('types_shipments');
        Schema::dropIfExists('shipments_status');

    }
}
