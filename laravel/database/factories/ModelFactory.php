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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Customer::class, function (Faker\Generator $faker) {
    static $password;

    return [
	'customer_id' => $faker->randomNumber(4),
        'customer_name' => $faker->name,
	'username' => $faker->unique()->userName,
        'password' => $password ?: $password = bcrypt('secret'),
        'mobile_phone' => $faker->e164PhoneNumber,
        'email_address' => $faker->unique()->safeEmail,
	'address' => $faker->address,
    ];
});
