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
            DB::table('areas')->insert(['name' => 'Administración']);
            DB::table('areas')->insert(['name' => 'Sistemas']);
            DB::table('areas')->insert(['name' => 'Publicidad']);
            DB::table('areas')->insert(['name' => 'Ventas']);
        });

        for($i=0;$i<1;$i++)
        {
            $sueldo=$faker->randomFloat( $nbMaxDecimals = 0, $min= 1000, $max=2000);

            $id_Employee = DB::table('employees')->insertGetId(array(
                'user_id' => 1,
                'area_id' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=4),
                'name' => $faker->name,
                'sex' => $faker->randomElement($array = array ('M','F')),
                'salary' => $sueldo,
                'break' => 45,
            ));

            for($y=1;$y<=5;$y++){
                DB::table('days_employees')->insertGetId(array(
                    'day_id' => $y,
                    'employee_id' => $id_Employee,
                    'start_time' => '090000',
                    'end_time'  =>  '190000',
                ));
            }

            for($x=1;$x<=31;$x++) {

                /*
                *  LUNCHES
                */
                $ini = $faker->randomFloat($nbMaxDecimals = 0, $min = 10, $max = 20);
                $id_lunches = DB::table('lunches')->insertGetId(array(
                    'employee_id' => $id_Employee,
                    'start_time' => '123000',
                    'end_time' => '13'.$ini.'00',
                    'date' => '2016-05-' . $x
                ));
                if ((integer)$ini > 15)$this->discounts_lunches($id_lunches, $ini);


                /*
                *  ASSISTS
                */
                $ini = $faker->randomFloat($nbMaxDecimals = 0, $min = 8, $max = 9);
                if ($ini == 8){
                    $ini .= $faker->randomFloat($nbMaxDecimals = 0, $min = 45, $max = 59);
                } else if ($ini == 9){
                    $min = $faker->randomFloat( $nbMaxDecimals = 0, $min= 0, $max=15);
                    if($min < 10)$ini .= '0'.$min; else $ini .= $min;
                } else {
                    $ini = '900';
                }
                $end = '190000';
                $conciliate = 0;
                if ($x == 15 || $x == 30){
                    $end = '200000';
                    $conciliate = 1;
                }

                $id_assist = DB::table('assists')->insertGetId(array(
                    'employee_id' => $id_Employee,
                    'start_time' => '0'.$ini.'00',
                    'end_time' => $end,
                    'amount' => 40.5,
                    'conciliate' =>  $conciliate, //$faker->boolean(),
                    'justification' => 0, //$faker->boolean(),
                    'date' => '2016-05-'.$x
                ));
                if ($conciliate) $this->extras($id_assist);
                if ((integer)$ini > 900)$this->discounts_assists($id_assist, $ini);

            }

            DB::table('bonuses')->insertGetId(array(
                'employee_id' => $id_Employee,
                'amount' => 200,
                'description' => 'Meta cumplida Mayo',
            ));
        }
    }

    private function discounts_assists($id, $ini){
        $minutes = (integer)$ini - 900;
        $amount = $minutes * 0.08966587;
        DB::table('discounts_assists')->insert(array(
            'assist_id' => $id,
            'amount' => $amount,
            'minutes' => $minutes,
        ));
    }

    private function discounts_lunches($id, $ini){
        $minutes = ((integer)$ini - 15);
        $amount = $minutes * 0.08966587;
        DB::table('discounts_lunches')->insertGetId(array(
            'lunch_id' => $id,
            'amount' => $amount,
            'minutes' => $minutes
        ));
    }

    private function extras($id){
        $amount = 60 * 0.08966587;
        DB::table('extras')->insertGetId(array(
            'assist_id' => $id,
            'amount' => $amount,
            'minutes' => 60,
        ));
    }

}
