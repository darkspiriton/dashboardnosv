<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableAuxproductAddObserve extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auxproducts',function(Blueprint $table){
            $table->string('observe_detail',100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auxproducts',function(Blueprint $table){
            $table->dropColumn('observe_detail');
        });
    }
}
