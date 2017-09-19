<?php

use App\Models\Member;
use Faker\Generator as Faker;

$factory->define(Member::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'address' => $faker->streetAddress,
        'phone' => $faker->phoneNumber,
        'sex' => $faker->randomElement(['M', 'F']),
        'active' => $faker->randomElement([1, 0]),
        'joined_at' => $faker->dateTimeBetween('-2 years'),
    ];
});
