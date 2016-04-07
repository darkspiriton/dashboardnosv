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
        $faker = Faker::create();
        DB::table('users')->insert([
            'first_name'=>  'God',
            'last_name' =>  'Administrator',
            'email'     =>  'admin@nosvenden.com',
            'phone'     =>  '555-5555',
            'address'   =>  'Vasco da gama 269 - La Molina',
            'Birth_date' =>  $faker->date(),
            'sex'       =>  'M',
            'photo'     =>  '1.jpg',
            'role_id'   =>  1,
            'user'      =>  'Admin',
            'password'  =>  bcrypt('123456'),
        ]);
        factory(Dashboard\User::class, 10)->create();
    }
}
