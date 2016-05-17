<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanillaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('days', function(Blueprint $table){
            $table->increments('id');
            $table->string('day');
        });

        Schema::create('areas', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('employees', function(Blueprint $table){
            $table->increments('id');
            $table->integer('area_id')->unsigned();
            $table->string('name');
            $table->char('sex');
            $table->float('salary');
            $table->integer('break');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->timestamps();
        });

        Schema::create('days_employees', function(Blueprint $table){
            $table->increments('id');
            $table->integer('day_id')->unsigned();
            $table->integer('employee_id')->unsigned();
            $table->time('start_time');
            $table->time('end_time');
            $table->foreign('day_id')->references('id')->on('days');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->timestamps();
        });

        Schema::create('lunches', function(Blueprint $table){
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->time('start_time');
            $table->time('end_time');
            $table->date('date');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->timestamps();
        });

        Schema::create('bonuses', function(Blueprint $table){
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->float('amount');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->timestamps();
        });

        Schema::create('salaries', function(Blueprint $table){
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->float('amount');
            $table->float('extras');
            $table->float('discounts');
            $table->float('bonuses');
            $table->integer('t_late');
            $table->integer('t_early');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->timestamps();
        });

        Schema::create('assists', function(Blueprint $table){
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->time('start_time');
            $table->time('end_time');
            $table->date('date');
            $table->boolean('conciliate');
            $table->boolean('justification');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->timestamps();
        });

        Schema::create('discounts_assists', function(Blueprint $table){
            $table->increments('id');
            $table->integer('assist_id')->unsigned();
            $table->float('amount');
            $table->float('minutes');
            $table->foreign('assist_id')->references('id')->on('assists');
            $table->timestamps();
        });

        Schema::create('discounts_lunches', function(Blueprint $table){
            $table->increments('id');
            $table->integer('lunches_id')->unsigned();
            $table->float('amount');
            $table->float('minutes');
            $table->foreign('lunches_id')->references('id')->on('lunches');
            $table->timestamps();
        });

        Schema::create('extras', function(Blueprint $table){
            $table->increments('id');
            $table->integer('assist_id')->unsigned();
            $table->float('amount');
            $table->float('minutes');
            $table->float('reconciled');
            $table->foreign('assist_id')->references('id')->on('assists');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts_lunches');
        Schema::dropIfExists('lunches');
        Schema::dropIfExists('bonuses');
        Schema::dropIfExists('salaries');
        Schema::dropIfExists('extras');
        Schema::dropIfExists('discounts_assists');
        Schema::dropIfExists('assists');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('days_employees');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('days');
    }
}
