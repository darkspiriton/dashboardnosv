<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUbigeoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubigeos', function(Blueprint $table){
            $table->string('UBIDEP',2);
            $table->string('UBIDEN',100);
            $table->string('UBIPRV',4);
            $table->string('UBIPRN',100);
            $table->string('UBIDST',6)->primary();
            $table->string('UBIDSN',100);
            $table->string('STATUS',1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ubigeos');
    }
}
