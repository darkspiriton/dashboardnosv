<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableProductAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auxproducts',function(Blueprint $table){
           $table->boolean('payment_status') ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auxproducts', function(Blueprint $table){
           $table->dropColumn('payment_status'); 
        });
    }
}
