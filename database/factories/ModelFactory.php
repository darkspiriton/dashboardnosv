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
        'password'  =>  bcrypt('123456')
    ];
});

$factory->define(\Dashboard\Models\Customer\Customer::class, function (Generator $faker) {
    return [
        'name'  =>  $faker->name,
        'age'   =>  $faker->date(),
        'phone' => $faker->randomFloat($nbMaxDecimals=0,$min=900000000,$max=999999999),
        'status'   =>  $faker->randomFloat($nbMaxDecimals=0,$min=0,$max=1)
    ];
});

$factory->define(\Dashboard\Models\Customer\Phone::class, function (Generator $faker) {
    return [
        'number'        =>  $faker->phoneNumber,
        'operator_id'   =>  $faker->randomFloat($nbMaxDecimals=0,$min=1,$max=6)
    ];
});

$factory->define(\Dashboard\Models\Customer\Social::class, function (Generator $faker) {
    return [
        'channel_url'=>  $faker->url,
        'channel_id' =>  $faker->randomFloat($nbMaxDecimals=0,$min=1,$max=3)
    ];
});

$factory->define(\Dashboard\Models\Customer\Address::class, function (Generator $faker) {
    return [
        'description'=>  $faker->text(200),
        'reference' =>  $faker->text(200),
        'ubigeo_id' =>  '150114'
    ];
});

$factory->define(\Dashboard\Models\Order\Order::class, function (Generator $faker) {
    return [
        'customer_id'=>  $faker->randomFloat($nbMaxDecimals=0,$min=1,$max=30),
        'user_id' =>  $faker->randomFloat($nbMaxDecimals=0,$min=1,$max=10),
        'status_id' =>  $faker->randomFloat($nbMaxDecimals=0,$min=1,$max=3)
    ];
});

$factory->define(\Dashboard\Models\Order\Detail::class , function (Generator $faker) {
    return [
        'kardex_id'=>  $faker->randomFloat($nbMaxDecimals=0,$min=1,$max=50),
        'price' =>  $faker->randomFloat($nbMaxDecimals=2,$min=35,$max=70)
    ];
});

$factory->define(\Dashboard\Models\Shipment\Shipment::class , function (Generator $faker) {
    return [
        'address_id'=>  $faker->randomFloat($nbMaxDecimals=0,$min=1,$max=60),
        'status_id' =>  $faker->randomFloat($nbMaxDecimals=0,$min=1,$max=3),
        'type_ship_id' =>  $faker->randomFloat($nbMaxDecimals=0,$min=1,$max=3),
        'date' =>  $faker->date(),
        'cost' =>  $faker->randomFloat($nbMaxDecimals=2,$min=10,$max=30)
    ];
});

$factory->define(\Dashboard\Models\Order\Call::class , function (Generator $faker) {
    return [
        'status_id'=>  $faker->randomFloat($nbMaxDecimals=0,$min=1,$max=3),
        'observation' =>  $faker->text(200),
        'date' =>  $faker->date()
    ];
});