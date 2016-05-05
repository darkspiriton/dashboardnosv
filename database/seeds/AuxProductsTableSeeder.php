<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AuxProductsTableSeeder extends Seeder
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
            DB::table('sizes')->insert(['name' => 'XS']);
            DB::table('sizes')->insert(['name' => 'S']);
            DB::table('sizes')->insert(['name' => 'M']);
            DB::table('sizes')->insert(['name' => 'L']);
            DB::table('sizes')->insert(['name' => 'XL']);
        });

        for ($i=0;$i<10;$i++){
            DB::table('colors')->insert([
                'name' => $faker->colorName,
            ]);
        }

        DB::transaction(function () {
            DB::table('providers')->insert(['name' => 'Proveedor 1']);
            DB::table('providers')->insert(['name' => 'Proveedor 2']);
            DB::table('providers')->insert(['name' => 'Proveedor 3']);
            DB::table('providers')->insert(['name' => 'Proveedor 4']);
            DB::table('providers')->insert(['name' => 'Proveedor 5']);
            DB::table('providers')->insert(['name' => 'Proveedor 6']);
            DB::table('providers')->insert(['name' => 'Proveedor 7']);
        });

        for($i=0;$i<20;$i++)
        {
            $randomSize = $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=5);
            $randomColor = $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=10);
            $randomProvider = $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=7);

            $idAlarm = DB::table('alarms')->insertGetId(array(
                'day' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 2, $max=10),
                'count' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 2, $max=10),
            ));

            $idProduct = DB::table('auxproducts')->insertGetId(array(
                'cod' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=2000),
                'provider_id' => $randomProvider,
                'color_id' => $randomColor,
                'size_id' => $randomSize,
                'alarm_id' => $idAlarm,
                'name' => $faker->company,               
                'status' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 0, $max=1),
            ));
            for($j=0;$j<5;$j++){
                DB::table('auxmovements')->insertGetId(array(
                    'product_id' => $idProduct,
                    'date_shipment' => $faker->dateTime($max = 'now', $timezone = date_default_timezone_get()),
                    'situation' => $faker->word,
                    'status' => $faker->word,
                ));
            }
        }
    }
}
