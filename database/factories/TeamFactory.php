<?php

use Faker\Generator as Faker;

$factory->define(App\Team::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'strength' => $faker->numberBetween(1, 10),
    ];
});
