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
        });

        DB::transaction(function () {
            DB::table('attributes')->insert(['type_id' => 1,'valor'=> 'MARCA1' ]);
            DB::table('attributes')->insert(['type_id' => 1,'valor'=> 'MARCA2' ]);
            DB::table('attributes')->insert(['type_id' => 1,'valor'=> 'MARCA3' ]);
            DB::table('attributes')->insert(['type_id' => 1,'valor'=> 'MARCA4' ]);

            DB::table('attributes')->insert(['type_id' => 2,'valor'=> 'COLOR1' ]);
            DB::table('attributes')->insert(['type_id' => 2,'valor'=> 'COLOR2' ]);
            DB::table('attributes')->insert(['type_id' => 2,'valor'=> 'COLOR3' ]);
            DB::table('attributes')->insert(['type_id' => 2,'valor'=> 'COLOR4' ]);

            DB::table('attributes')->insert(['type_id' => 3,'valor'=> 'TALLA1' ]);
            DB::table('attributes')->insert(['type_id' => 3,'valor'=> 'TALLA2' ]);
            DB::table('attributes')->insert(['type_id' => 3,'valor'=> 'TALLA3' ]);
            DB::table('attributes')->insert(['type_id' => 3,'valor'=> 'TALLA4' ]);
        });

        for($i=0;$i<20;$i++)
        {
            $idProduct = DB::table('products')->insertGetId(array(
                'name' => $faker->colorName,
                'price' => $faker->randomFloat( $nbMaxDecimals = 2, $min= 9, $max=100),
                'image' => $faker->imageUrl($width = 200, $height = 180),
                'product_code' => $faker->numberBetween($min = 1, $max = 90000),
                'status' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 0, $max=1),
            ));

            $random = $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=5);
            for($z=0;$z<=$random;$z++){
                $idKardex =  DB::table('kardexs')->insertGetId(array(
                    'product_id' => $idProduct,
                    'product_cod' => $z,
                    'stock' => $faker->boolean(40) ,
                ));

                for($j=1;$j<=3;$j++){
                    DB::table('kardexs_attributes')->insertGetId(array(
                        'kardex_id' => $idKardex,
                        'attribute_id' => $j
                    ));
                }
            }
        }
    }
}
