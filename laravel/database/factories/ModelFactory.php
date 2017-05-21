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

/**
 * Customer model factory generator  
 **/
$factory->define(App\Customer::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'customer_name' => $faker->name,
	    'username' => $faker->unique()->userName,
        'password' => $password ?: $password = bcrypt('secret'),
        'mobile_phone' => '04'.rand(10000000,99999999),
        'email_address' => $faker->unique()->safeEmail,
	    'address' => $faker->streetAddress,
    ];
});

/**
 * Business model factory generator  
 **/
$factory->define(App\Business::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'business_name' => $faker->company,
	'owner_name' => $faker->name,
	'username' => $faker->unique()->userName,
        'mobile_phone' => '04'.rand(10000000,99999999),
        'email_address' => $faker->unique()->safeEmail,
	'address' => $faker->streetAddress,
    ];
});


/**
 * Employee model factory generator  
 **/
$factory->define(App\Employee::class, function (Faker\Generator $faker) {

    $weekdays = array(
        'Mon',
        'Tue',
        'Wed',
        'Thu',
        'Fri',
        'Sat',
        'Sun'
    );

    $noOfDays = rand(2,7);
    $rand_key = array_rand($weekdays, $noOfDays);
    $availability = "";
    foreach ($rand_key as $key)
    {
        $availability = $availability . " " . $weekdays[$key];
    }

    return [
        'employee_name' => $faker->name,
        'TFN'           => rand(100000000,999999999),
        'mobile_phone'  => '04'.rand(10000000,99999999),
        'available_days' => $availability
    ];
});
