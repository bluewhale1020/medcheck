<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\MessageBoard;
use Faker\Generator as Faker;

$factory->define(MessageBoard::class, function (Faker $faker) {
    return [
        'user_id'=>$faker->randomElement([1,3,7]),
        'title' =>$faker->sentence(1),
        'message' => $faker->sentence(5),
    ];
});
