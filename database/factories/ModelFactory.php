<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create Models for testing and seeding your
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
        'Birth_date' =>  $faker->date(),
        'sex'       =>  'M',
        'photo'     =>  $faker->imageUrl($width= 50 , $height=50),
        'role_id'   =>  $faker->randomFloat($nbMaxDecimals=0,$min=2,$max=5),
        'status' => $faker->randomFloat( $nbMaxDecimals = 0, $min= 0, $max=1),
        'password'  =>  bcrypt('123456'),
    ];
});

$factory->define(\Dashboard\Models\Customer\Customer::class, function (Generator $faker) {
    return [
        'name'  =>  $faker->name,
        'age'   =>  $faker->randomFloat($nbMaxDecimals=0,$min=18,$max=30),
    ];
});

$factory->define(\Dashboard\Models\Customer\Phone::class, function (Generator $faker) {
    return [
        'number'        =>  $faker->phoneNumber,
        'operator_id'   =>  $faker->randomFloat($nbMaxDecimals=0,$min=1,$max=6),
    ];
});

$factory->define(\Dashboard\Models\Customer\Social::class, function (Generator $faker) {
    return [
        'channel_url'=>  $faker->url,
        'channel_id' =>  $faker->randomFloat($nbMaxDecimals=0,$min=1,$max=3),
    ];
});

$factory->define(\Dashboard\Models\Customer\Address::class, function (Generator $faker) {
    return [
        'description'=>  $faker->text(200),
        'reference' =>  $faker->text(200),
        'ubigeo_id' =>  '150114',
    ];
});
