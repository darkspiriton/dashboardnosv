<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class QuestionnairesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        DB::transaction(function () {
            DB::table('categories')->insert(['name' => 'Moda']);
            DB::table('categories')->insert(['name' => 'Electronico']);
            DB::table('categories')->insert(['name' => 'Servicio']);
            DB::table('categories')->insert(['name' => 'Inmueble']);
        });

        for($k=0;$k<5;$k++){
            DB::table('aux2customers')->insert(array(
                'user'=>$faker->unique()->email,
                'name'=>$faker->name,
                'sexo'=>$faker->randomElement($array = array ('M','F')),
                'edad'=>$faker->randomFloat( $nbMaxDecimals = 0, $min= 18, $max=60),
            ));
        }

        for($z=0;$z<5;$z++){
            DB::table('aux2products')->insertGetId(array(
                'category_id' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=4),
                'name' => $faker->name,
            ));
        }

        for($i=0;$i<1;$i++){
            $questionnaire_id= DB::table('questionnaires')->insertGetId(array(
                'category_id' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=4),
            ));

            for($j=0;$j<5;$j++){
                $question_id= DB::table('questions')->insertGetId(array(
                    'question' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                ));

                DB::table('questionnaires_questions')->insertGetId(array(
                    'questionnaire_id' => $questionnaire_id,
                    'question_id' => $question_id,
                ));

                for($x=0;$x<2;$x++){
                    $option_id=DB::table('options')->insertGetId(array(
                        'question_id' => $question_id,
                        'option' => $faker->sentence($nbWords = 6, $variableNbWords = true)
                    ));

                    DB::table('answers_customers')->insert(array(
                        'customer_id' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=5),
                        'option_id' => $option_id,
                        'questionnaire_id' => $questionnaire_id,
                    ));

                    DB::table('answers_products')->insert(array(
                        'product_id' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=5),
                        'option_id' => $option_id,
                        'questionnaire_id' => $questionnaire_id,
                    ));

                }
            }
        }
    }
}
