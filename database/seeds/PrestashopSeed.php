<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class PrestashopSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('es_ES');
        // DB::transaction(function(){
        //     DB::table('users_requests')->insert()
        // });

        $id_user = DB::table('users_prestashop')->insertGetId(array(
            'name'=> $faker->name,
            'email'=> $faker->unique()->email,
            'phone'=> $faker->e164PhoneNumber            
        ));

        for($i=0;$i<4;$i++){
            $id_request = DB::table('requests_prestashop')->insertGetId(array(
                'status'=>$faker->randomFloat( $nbMaxDecimals = 0, $min= 0, $max=1),
                'total_price'=>500,
                'user_id'=>$id_user,
                'created_at'=>$faker->dateTimeThisMonth($max = 'now')                
            ));

            for($j=0;$j<5;$j++){
                DB::table('products_prestashop')->insert(array(
                    'name'=>$faker->company,
                    'url_image'=>'http://www.nosvenden.com/img/p/1/1/6/2/1162-large_default.jpg',
                    'url_product'=>'http://www.nosvenden.com/index.php?id_product=139&controller=product',
                    'stock'=>'En Stock',
                    'price'=>100.00,
                    'cant'=>1,
                    'request_id'=>$id_request,                    
                ));
            }
        }



    }
}
