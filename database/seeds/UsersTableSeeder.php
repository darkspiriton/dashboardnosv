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

        DB::table('customers')->delete();
        DB::table('operators')->delete();
        DB::table('channels')->delete();
        DB::table('users')->delete();
        DB::table('roles')->delete();

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
                'first_name'=>  'God',
                'last_name' =>  'Administrator',
                'email'     =>  'god@nosvenden.com',
                'phone'     =>  '555-5555',
                'address'   =>  'Vasco da gama 269 - La Molina',
                'Birth_date' =>  $faker->date(),
                'sex'       =>  'M',
                'photo'     =>  '1.jpg',
                'role_id'   =>  1,
                'status'    =>  1,
                'password'  =>  bcrypt('123456'),
            ],
            [
                'first_name'=>  'Vendedor',
                'last_name' =>  'Administrator',
                'email'     =>  'ventas@nosvenden.com',
                'phone'     =>  '555-5555',
                'address'   =>  'Vasco da gama 269 - La Molina',
                'Birth_date' =>  $faker->date(),
                'sex'       =>  'M',
                'photo'     =>  '1.jpg',
                'role_id'   =>  3,
                'status'    =>  1,
                'password'  =>  bcrypt('123456'),
            ],
            [
                'first_name'=>  'Administracion',
                'last_name' =>  'Administrator',
                'email'     =>  'administracion@nosvenden.com',
                'phone'     =>  '555-5555',
                'address'   =>  'Vasco da gama 269 - La Molina',
                'Birth_date' =>  $faker->date(),
                'sex'       =>  'M',
                'photo'     =>  '1.jpg',
                'role_id'   =>  2,
                'status'    =>  1,
                'password'  =>  bcrypt('123456'),
            ],
            [
                'first_name'=>  'Coordinador',
                'last_name' =>  'Administrator',
                'email'     =>  'coordinador@nosvenden.com',
                'phone'     =>  '555-5555',
                'address'   =>  'Vasco da gama 269 - La Molina',
                'Birth_date' =>  $faker->date(),
                'sex'       =>  'M',
                'photo'     =>  '1.jpg',
                'role_id'   =>  4,
                'status'    =>  1,
                'password'  =>  bcrypt('123456'),
            ],
        ]);

        factory(Dashboard\User::class, 10)->create()->each(function($user){
                $user->customers()->saveMany(factory(\Dashboard\Models\Customer\Customer::class, 3)->make())->each(function($customer){
                $customer->phones()->saveMany(factory(\Dashboard\Models\Customer\Phone::class, 2)->make());
                $customer->socials()->save(factory(\Dashboard\Models\Customer\Social::class)->make());
                $customer->phones()->saveMany(factory(\Dashboard\Models\Customer\Address::class, 2)->make());
            });
        });

    }
}
