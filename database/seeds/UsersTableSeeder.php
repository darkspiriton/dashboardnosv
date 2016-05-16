<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::transaction(function () {

            DB::table('roles')->insert([
                ['abrev' => 'GOD', 'name' => 'GOD'],
                ['abrev' => 'ADM', 'name' => 'Administracion'],
                ['abrev' => 'VEN', 'name' => 'Vendedor'],
                ['abrev' => 'JVE', 'name' => 'Jefe de ventas'],
                ['abrev' => 'MOT', 'name' => 'Motorizado']
            ]);

            DB::table('operators')->insert([
                ['name' => 'Movistar'],
                ['name' => 'Claro'],
                ['name' => 'Entel'],
                ['name' => 'Bitel'],
                ['name' => 'Tuenti'],
                ['name' => 'Virgin Mobile']
            ]);

            DB::table('channels')->insert([
                ['name' => 'Facebook'],
                ['name' => 'Twitter'],
                ['name' => 'Whatsapp']
            ]);
        });

        $faker = Faker::create();
        DB::table('users')->insert([
            [
                'first_name'=>  'Root',
                'last_name' =>  'Administrador',
                'email'     =>  'miguel@nosvenden.com',
                'phone'     =>  '555-5555',
                'address'   =>  'Vasco da gama 269 - La Molina',
                'Birth_date' =>  $faker->date(),
                'sex'       =>  'H',
                'photo'     =>  '1.jpg',
                'role_id'   =>  1,
                'status'    =>  1,
                'password'  =>  bcrypt('12345678pw'),
            ],
            [
                'first_name'=>  'Root',
                'last_name' =>  'Administrador',
                'email'     =>  'susy@nosvenden.com',
                'phone'     =>  '555-5555',
                'address'   =>  'Vasco da gama 269 - La Molina',
                'Birth_date' =>  $faker->date(),
                'sex'       =>  'M',
                'photo'     =>  '1.jpg',
                'role_id'   =>  2,
                'status'    =>  1,
                'password'  =>  bcrypt('12345678pw'),
            ],
            [
                'first_name'=>  'Root',
                'last_name' =>  'Administrador',
                'email'     =>  'cynthia@nosvenden.com',
                'phone'     =>  '555-5555',
                'address'   =>  'Vasco da gama 269 - La Molina',
                'Birth_date' =>  $faker->date(),
                'sex'       =>  'M',
                'photo'     =>  '1.jpg',
                'role_id'   =>  2,
                'status'    =>  1,
                'password'  =>  bcrypt('12345678pw'),
            ],
            [
                'first_name'=>  'Coordinador',
                'last_name' =>  'Ventas',
                'email'     =>  'coordinador@nosvenden.com',
                'phone'     =>  '555-5555',
                'address'   =>  'Vasco da gama 269 - La Molina',
                'Birth_date' =>  $faker->date(),
                'sex'       =>  'M',
                'photo'     =>  '1.jpg',
                'role_id'   =>  4,
                'status'    =>  1,
                'password'  =>  bcrypt('coordinador123'),
            ],
            [
                'first_name'=>  'Vendedora1',
                'last_name' =>  'Vendedora',
                'email'     =>  'venta1@nosvenden.com',
                'phone'     =>  '555-5555',
                'address'   =>  'Vasco da gama 269 - La Molina',
                'Birth_date' =>  $faker->date(),
                'sex'       =>  'M',
                'photo'     =>  '1.jpg',
                'role_id'   =>  3,
                'status'    =>  1,
                'password'  =>  bcrypt('vendedora1123'),
            ],
            [
                'first_name'=>  'Vendedora2',
                'last_name' =>  'Vendedora',
                'email'     =>  'venta2@nosvenden.com',
                'phone'     =>  '555-5555',
                'address'   =>  'Vasco da gama 269 - La Molina',
                'Birth_date' =>  $faker->date(),
                'sex'       =>  'M',
                'photo'     =>  '1.jpg',
                'role_id'   =>  3,
                'status'    =>  1,
                'password'  =>  bcrypt('vendedora2123'),
            ],
        ]);

//        factory(Dashboard\User::class, 10)->create()->each(function($user){
//            $user->customers()->saveMany(factory(\Dashboard\Models\Customer\Customer::class, 3)->make())->each(function($customer){
//                $customer->phones()->saveMany(factory(\Dashboard\Models\Customer\Phone::class, 2)->make());
//                $customer->socials()->save(factory(\Dashboard\Models\Customer\Social::class)->make());
//                $customer->addresses()->saveMany(factory(\Dashboard\Models\Customer\Address::class, 2)->make());
//            });
//        });




    }
}
