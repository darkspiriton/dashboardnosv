<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UsersSystemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table('users')->insert([
            [
                'first_name'=>  'System',
                'last_name' =>  'Administrador',
                'email'     =>  'system@nosvenden.com',
                'phone'     =>  '555-5555',
                'address'   =>  'Vasco da gama 269 - La Molina',
                'Birth_date' =>  $faker->date(),
                'sex'       =>  'H',
                'photo'     =>  '1.jpg',
                'role_id'   =>  1,
                'status'    =>  1,
                'password'  =>  bcrypt('12345678pw'),
            ]
        ]);
    }
}
