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

        for($i=0;$i<100;$i++)
        {
            $idProduct = DB::table('products')->insertGetId(array(
                'name' => $faker->colorName,
                'price' => $faker->randomFloat( $nbMaxDecimals = 2, $min= 9, $max=100),
                'product_code' => $faker->numberBetween($min = 1, $max = 90000),
                'status' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 0, $max=1),
            ));
            for($j=1;$j<=4;$j++){
                DB::table('attributes')->insertGetId(array(
                    'product_id' => $idProduct,
                    'type_id' => $j,
                    'valor' =>  $faker->colorName,
                ));
            }
            $random = $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=10);
            for($z=0;$z<=$random;$z++){
                DB::table('kardexs')->insertGetId(array(
                    'product_id' => $idProduct,
                    'product_cod' => $z,
                    'stock' => $faker->boolean(40) ,
                ));
            }
        }
    }
}
