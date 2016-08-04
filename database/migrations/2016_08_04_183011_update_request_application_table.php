<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRequestApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_requests',function(Blueprint $table){
            $table->string('phone',50)->change();
            $table->boolean('status')->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_requests',function(Blueprint $table){
            $table->integer('phone')->change();
            $table->dropColumn('status');
        });
    }
}
