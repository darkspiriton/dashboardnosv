<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 100);
            $table->string('last_name', 150);
            $table->string('email', 100)->unique();
            $table->string('phone');
            $table->string('address');
            $table->date('Birth_date');
            $table->char('sex', 1);
            $table->string('photo', 50);
            $table->integer('role_id')->unsigned();
            $table->string('user');
            $table->string('password');
            $table->string('token')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });

        /*
         *
            Relationships
         *
         */

        Schema::table('users', function(Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
