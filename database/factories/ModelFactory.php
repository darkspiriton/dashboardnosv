<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Faker\Generator;

$factory->define(Dashboard\User::class, function (Generator $faker) {
    return [
        'first_name'=>  $faker->firstNameMale,
        'last_name' =>  $faker->lastName,
        'email'     =>  $faker->safeEmail,
        'phone'     =>  $faker->phoneNumber,
        'address'   =>  $faker->address,
        'Birthdate' =>  $faker->date(),
        'sex'       =>  'M',
        'photo'     =>  $faker->imageUrl($width= 50 , $height=50),
        'role_id'   =>  $faker->randomFloat($nbMaxDecimals=0,$min=2,$max=5),
        'user'      =>  $faker->userName,
        'password'  =>  bcrypt('123456'),
    ];
});
