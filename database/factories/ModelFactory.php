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

$factory->define(CVS\User::class, function ($faker) {
    return [
        'email' => $faker->email,
        'password' => bcrypt('password'),
        'firstname' => $faker->firstname,
        'lastname' => $faker->lastname,
        'phone' => $faker->phoneNumber,
        'email_notifications' => $faker->boolean,
        'sms_notifications' => $faker->boolean,
        'organizer' => $faker->boolean,
        'remember_token' => str_random(10),
    ];
});

$factory->define(CVS\Candidate::class, function ($faker) {
    return [
        'grade' => $faker->randomElement(['L3', 'M1', 'M2']),
        'printing_option' => $faker->boolean(),
    ];
});

$factory->define(CVS\Recruiter::class, function ($faker) {
    return [
        'parking_option' => $faker->boolean,
        'lunch_option' => $faker->boolean,
    ];
});

$factory->define(CVS\Company::class, function ($faker) {
    return [
        'name' => $faker->company,
    ];
});

$factory->define(CVS\Location::class, function ($faker) {
    return [
        'name' => $faker->sentence(3),
    ];
});

$factory->define(CVS\Slot::class, function ($faker) {
    return [
        'begins_at' => $faker->dateTime,
        'ends_at' => $faker->dateTime,
    ];
});