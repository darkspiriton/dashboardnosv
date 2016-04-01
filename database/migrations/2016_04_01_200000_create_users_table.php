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
            $table->date('Birthdate');
            $table->char('sex', 1);
            $table->string('photo', 50);
            $table->integer('role_id')->unsigned()->increments();
            $table->string('user');
            $table->string('password');
            $table->boolean('state')->default(0);
            $table->timestamps();
        });

        Schema::table('users', function($table) {
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
