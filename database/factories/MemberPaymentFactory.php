<?php

use App\Models\Member;
use App\Models\MemberPayment;
use Faker\Generator as Faker;

$factory->define(MemberPayment::class, function (Faker $faker) {
    return [
        'member_id' => Member::all()->random()->id,
        'value' => 100,
        'valid_from' => $faker->dateTimeBetween('-2 years'),
        'valid_until' => $faker->dateTimeBetween('-2 years'),
    ];
});
