<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductsTableSeeder extends Seeder
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
            DB::table('types_attributes')->insert(['name' => 'Marca']);
            DB::table('types_attributes')->insert(['name' => 'Color']);
            DB::table('types_attributes')->insert(['name' => 'Talla']);
            DB::table('types_attributes')->insert(['name' => 'Categoria']);
        });


        for($i=0;$i<20;$i++)
        {
            $idProduct = DB::table('products')->insertGetId(array(
                'name' => $faker->colorName,
                'price' => $faker->randomFloat( $nbMaxDecimals = 2, $min= 9, $max=100),
                'product_code' => $faker->unique()->randomDigit ,
            ));
            for($i=0;$i<4;$i++){

                DB::table('attributes')->insertGetId(array(
                    'product_id' => $idProduct,
                    'type_id' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 0, $max=3),
                    'valor' =>  $faker->colorName,
                ));

            }

        }
    }
}
