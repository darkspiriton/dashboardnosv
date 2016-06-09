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
            DB::table('types_processes')->insert(['name' => 'Proceso']);
            DB::table('types_processes')->insert(['name' => 'Foto']);
            DB::table('types_processes')->insert(['name' => 'Envio']);
        });

        DB::transaction(function () {
            DB::table('types_socials')->insert(['name' => 'Facebook']);
            DB::table('types_socials')->insert(['name' => 'Twitter']);
            DB::table('types_socials')->insert(['name' => 'Instagram']);
            DB::table('types_socials')->insert(['name' => 'Youtube']);
            DB::table('types_socials')->insert(['name' => 'Pinterest']);
            DB::table('types_socials')->insert(['name' => 'Google+']);
        });

        DB::transaction(function () {
            DB::table('sizes')->insert(['name' => 'XS']);
            DB::table('sizes')->insert(['name' => 'S']);
            DB::table('sizes')->insert(['name' => 'M']);
            DB::table('sizes')->insert(['name' => 'L']);
            DB::table('sizes')->insert(['name' => 'XL']);
        });

        DB::transaction(function () {
            DB::table('types')->insert(['name' => 'Tipo1']);
            DB::table('types')->insert(['name' => 'Tipo2']);
            DB::table('types')->insert(['name' => 'Tipo3']);
            DB::table('types')->insert(['name' => 'Tipo4']);
            DB::table('types')->insert(['name' => 'Tipo5']);
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
                'cod' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max=10000),
                'provider_id' => $randomProvider,
                'color_id' => $randomColor,
                'size_id' => $randomSize,
                'alarm_id' => $idAlarm,
                'cost_provider' => $faker->randomFloat( $nbMaxDecimals = 2, $min= 20, $max=50),
                'utility' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 10, $max=30),
                'name' => $faker->company,               
                'status' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 0, $max=1),
            ));
            DB::table('types_auxproducts')->insert(array(
                'type_id' => $faker->randomFloat($nbMaxDecimals = 0, $min= 1, $max= 5),
                'product_id' => $idProduct,
            ));

            for($j=0;$j<5;$j++){
                DB::table('auxmovements')->insertGetId(array(
                    'product_id' => $idProduct,
                    'date_shipment' => $faker->dateTime($max = 'now', $timezone = date_default_timezone_get()),
                    'situation' => $faker->word,
                    'status' => $faker->word,
                    'discount' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 10, $max=15),
                ));
            }

            for($h=0;$h<5;$h++){
                $idPublicity=DB::table('publicities')->insertGetId(array(
                    'product_id'=>$idProduct,
                    'date'=> $faker->dateTime($max = 'now', $timezone = date_default_timezone_get()),
                    'status'=>$faker->boolean($chanceOfGettingTrue = 50),
                ));

                for($r=1;$r<=3;$r++) {
                    DB::table('processes')->insertGetId(array(
                        'publicity_id' =>$idPublicity,
                        'type_process_id'=>$r,
                        'date'=> $faker->dateTime($max = 'now', $timezone = date_default_timezone_get()),
                        'status'=>$faker->boolean($chanceOfGettingTrue = 50),
                    ));
                };

                for($y=1;$y<=5;$y++) {
                    DB::table('auxsocials')->insert(array(
                        'date' => $faker->dateTime($max = 'now', $timezone = date_default_timezone_get()),
                        'publicity_id' =>$idPublicity,
                        'type_social_id'=>$y,
                    ));
                };
            }
        }

        for($x=0;$x<5;$x++){
            $outfitid=DB::table('outfits')->insertGetId(array(
               'name'=>$faker->colorName,
               'cod' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 100, $max= 1500),
               'price' => $faker->randomFloat( $nbMaxDecimals = 2, $min= 20, $max= 50),
            ));

            $exist=$faker->boolean($chanceOfGettingTrue = 40);
            $productid=$faker->randomFloat( $nbMaxDecimals = 0, $min= 1, $max= 5);
            $productid2=$faker->randomFloat( $nbMaxDecimals = 0, $min= 6, $max= 11);
            
            if($exist==true){
                DB::table('products_outfits')->insert(array(
                   'product_id'=>$productid,
                   'outfit_id'=>$outfitid,
                ));
            }
            
            DB::table('settlements')->insert(array(
               'price'=> $faker->randomFloat( $nbMaxDecimals = 2, $min= 20, $max= 50),
               'product_id'=>$productid2,
            ));

        }

    }
}
