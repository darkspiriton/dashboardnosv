<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuestionarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('questionnaires', function(Blueprint $table){
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->string('description');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->timestamps();
        });

        Schema::create('aux2products', function(Blueprint $table){
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->string('name');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->timestamps();
        });

        Schema::create('questions', function(Blueprint $table){
            $table->increments('id');
            $table->string('question');
            $table->timestamps();
        });

        Schema::create('questionnaires_questions', function(Blueprint $table){
            $table->integer('questionnaire_id')->unsigned();
            $table->integer('question_id')->unsigned();
            $table->foreign('questionnaire_id')->references('id')->on('questionnaires');
            $table->foreign('question_id')->references('id')->on('questions');
            $table->timestamps();
        });

        Schema::create('options', function(Blueprint $table){
            $table->increments('id');
            $table->integer('question_id')->unsigned();
            $table->string('option');
            $table->foreign('question_id')->references('id')->on('questions');
            $table->timestamps();
        });

        Schema::create('aux2customers', function(Blueprint $table){
            $table->increments('id');
            $table->string('user');
            $table->string('name');
            $table->string('sexo');
            $table->integer('edad');
            $table->timestamps();
        });

        Schema::create('answers_customers', function(Blueprint $table){
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->integer('option_id')->unsigned();
            $table->integer('questionnaire_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('aux2customers');
            $table->foreign('option_id')->references('id')->on('options');
            $table->foreign('questionnaire_id')->references('id')->on('questionnaires');
            $table->timestamps();
        });

        Schema::create('answers_products', function(Blueprint $table){
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('option_id')->unsigned();
            $table->integer('questionnaire_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('aux2products');
            $table->foreign('option_id')->references('id')->on('options');
            $table->foreign('questionnaire_id')->references('id')->on('questionnaires');
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
        Schema::dropIfExists('answers_customers');
        Schema::dropIfExists('answers_products');
        Schema::dropIfExists('questionnaires_questions');
        Schema::dropIfExists('options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('aux2products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('questionnaires');
    }
}
