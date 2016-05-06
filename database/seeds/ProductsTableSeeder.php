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
            DB::table('status_interests')->insert(['name' => 'Status 1']);
            DB::table('status_interests')->insert(['name' => 'Status 2']);
            DB::table('status_interests')->insert(['name' => 'Status 3']);
            DB::table('status_interests')->insert(['name' => 'Status 4']);
            DB::table('status_interests')->insert(['name' => 'Status 5']);
            DB::table('status_interests')->insert(['name' => 'Status 6']);
            DB::table('status_interests')->insert(['name' => 'Status 7']);
        });

        DB::transaction(function () {
            DB::table('types_scope')->insert(['name' => 'Tiempo 1']);
            DB::table('types_scope')->insert(['name' => 'Tiempo 2']);
            DB::table('types_scope')->insert(['name' => 'Tiempo 3']);
            DB::table('types_scope')->insert(['name' => 'Tiempo 4']);
            DB::table('types_scope')->insert(['name' => 'Tiempo 5']);
            DB::table('types_scope')->insert(['name' => 'Tiempo 6']);
            DB::table('types_scope')->insert(['name' => 'Tiempo 7']);
        });

        DB::transaction(function () {
            DB::table('types_products')->insert(['name' => 'Bolsa']);
            DB::table('types_products')->insert(['name' => 'Blusa']);
            DB::table('types_products')->insert(['name' => 'Ropa']);
            DB::table('types_products')->insert(['name' => 'Zapatilla']);
            DB::table('types_products')->insert(['name' => 'Electronico']);
            DB::table('types_products')->insert(['name' => 'Zapato']);
            DB::table('types_products')->insert(['name' => 'Pantalon']);
        });

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
                'type_product_id' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=7),
            ));

            $random1 = $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=5);
            for($x=0;$x<=$random1;$x++){
                $global_attribute_id = DB::table('groups_attributes')->insertGetId(array(
                    'product_id' => $idProduct,
                ));
                for($j=1;$j<=3;$j++){

                    switch ($j){
                        case 1:
                            $t=$faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=4);
                            break;
                        case 2:
                            $t=$faker->randomFloat( $nbMaxDecimals = 0, $min= 5, $max=8);
                            break;
                        case 3:
                            $t=$faker->randomFloat( $nbMaxDecimals = 0, $min= 9, $max=12);
                            break;
                    }

                    DB::table('attributes_kardexs')->insertGetId(array(
                        'group_attribute_id' => $global_attribute_id,
                        'attribute_id' => $t
                    ));
                }

                $random2 = $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=5);
                for($z=0;$z<=$random2;$z++){
                    DB::table('kardexs')->insertGetId(array(
                        'group_attribute_id' => $global_attribute_id,
                        'product_id' => $idProduct,
                        'stock' => $faker->boolean(40) ,
                    ));

                }
            }



        }
    }
}
