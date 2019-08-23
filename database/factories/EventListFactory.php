<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\EventList;
use Faker\Generator as Faker;

$factory->define(EventList::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(1),
        'type' => $faker->randomElement(['create','update','delete']), 
        'level' => $faker->numberBetween(1,5),
        'notes' => $faker->sentence(6),
        'created_at' => $faker->date,
        'updated_at' => $faker->date 
    ];
});
