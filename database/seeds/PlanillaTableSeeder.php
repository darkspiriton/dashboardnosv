<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PlanillaTableSeeder extends Seeder
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
            DB::table('days')->insert(['day' => 'Lunes']);
            DB::table('days')->insert(['day' => 'Martes']);
            DB::table('days')->insert(['day' => 'Miercoles']);
            DB::table('days')->insert(['day' => 'Jueves']);
            DB::table('days')->insert(['day' => 'Viernes']);
            DB::table('days')->insert(['day' => 'Sábado']);
            DB::table('days')->insert(['day' => 'Domingo']);
        });

        DB::transaction(function () {
            DB::table('areas')->insert(['name' => 'Publicidad']);
            DB::table('areas')->insert(['name' => 'Sistemas']);
            DB::table('areas')->insert(['name' => 'Ventas']);
            DB::table('areas')->insert(['name' => 'Administración']);
        });

        for($i=0;$i<10;$i++)
        {
            $sueldo=$faker->randomFloat( $nbMaxDecimals = 0, $min= 1000, $max=2000);

            $idEmploye = DB::table('employees')->insertGetId(array(
                'area_id' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=4),
                'name' => $faker->name,
                'sex' => $faker->randomElement($array = array ('H','M')),
                'sueldo' => $sueldo,
                'almuerzo' => 45,
            ));

            for($x=1;$x<=5;$x++){

                DB::table('days_employees')->insertGetId(array(
                    'day_id' => $x,
                    'employe_id' => $idEmploye,
                    'start_time' => '090000',
                    'end_time'  =>  '190000',
                    'created_at' => $faker->dateTime($max = 'now', $timezone = date_default_timezone_get()),
                ));

                $random1 = $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=10);
                for($j=1;$j<=$random1;$j++){

                    $idLunch = DB::table('lunches')->insertGetId(array(
                        'employe_id' => $idEmploye,
                        'start_time' => '123000',
                        'end_time'  =>  '011500',
                        'created_at' => $faker->dateTime($max = 'now', $timezone = date_default_timezone_get()),
                    ));

                    DB::table('discounts_lunches')->insertGetId(array(
                        'lunches_id' => $idLunch,
                        'amount' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=10),
                        'created_at' => $faker->dateTime($max = 'now', $timezone = date_default_timezone_get()),
                    ));
                }

                $random2 = $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=10);
                for($z=1;$z<=$random2;$z++){

                    DB::table('bonuses')->insertGetId(array(
                        'employe_id' => $idEmploye,
                        'amount' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=10),
                        'created_at' => $faker->dateTime($max = 'now', $timezone = date_default_timezone_get()),
                    ));
                }

                $random3 = $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=10);
                for($h=1;$h<=$random3;$h++){

                    DB::table('salaries')->insertGetId(array(
                        'employe_id' => $idEmploye,
                        'amount' => $sueldo,
                        'extras' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=10),
                        'discounts' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=10),
                        'bonuses' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=10),
                        'created_at' => $faker->dateTime($max = 'now', $timezone = date_default_timezone_get()),
                    ));
                }

                $random4 = $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=5);
                for($o=0;$o<=$random4;$o++){
                    $idAssist = DB::table('assists')->insertGetId(array(
                        'employe_id' => $idEmploye,
                        'start_time' => $faker->time($format = 'H:i:s', $max = 'now'),
                        'end_time' => $faker->time($format = 'H:i:s', $max = 'now'),
                        'type' => $faker->boolean(40),
                        'created_at' => $faker->dateTime($max = 'now', $timezone = date_default_timezone_get()),
                    ));

                    DB::table('extras')->insertGetId(array(
                        'assist_id' => $idAssist,
                        'amount' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=10),
                        'cant' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=100),
                        'reconciled' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=10),
                        'created_at' => $faker->dateTime($max = 'now', $timezone = date_default_timezone_get()),
                    ));

                    DB::table('discounts_assists')->insertGetId(array(
                        'assist_id' => $idAssist,
                        'amount' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=10),
                        'cant' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=10),
                        'created_at' => $faker->dateTime($max = 'now', $timezone = date_default_timezone_get()),
                    ));

                }
            }



        }
        
    }
}
