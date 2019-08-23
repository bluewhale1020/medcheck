<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Account;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        // 'id' => $faker->unique()->numberBetween(20,30),
        'kana' => $faker->kanaName,
        'name' => $faker->name,
        'id_number' => $faker->numerify('ID####') ,
        'birthdate' => $faker->dateTimeBetween('-80 years', '-20years')->format('Y-m-d'),
        'age' => $faker->numberBetween(20,60),
        'sex' => ($faker->boolean())? "男":"女",
        'department' => $faker->company,
        'created_at' => $faker->date,
        'updated_at' => $faker->date        
    ];
});
