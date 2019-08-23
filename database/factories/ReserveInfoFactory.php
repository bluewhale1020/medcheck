<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\ReserveInfo;
use App\Configuration;
use Faker\Generator as Faker;


$factory->define(ReserveInfo::class, function (Faker $faker) {
    return [
        // 'id' => $faker->unique()->numberBetween(31,40),
        'reception_list_id' => Configuration::getReceptionId(),
        'checkup_info_id' => $faker->unique()->randomNumber(5),
        'account_id' => factory('App\Account')->create()->id,//$faker->unique()->numberBetween(21,30),
        'serial_number' => $faker->unique()->numberBetween(101,200),
        'schedule_date' => '2019-06-11 12:30:00',
        'checkup_date' => '2019-06-11',
        'course' => '定期健診',
        'kenpo' => $faker->boolean(),
        'notes' => $faker->sentence(6),
        'check_in' => $faker->boolean(),
        'complete' => false,
        'created_at' => $faker->date,
        'updated_at' => $faker->date       

    ];
});


$factory->state(ReserveInfo::class, 'test_model', function() {
    return [
        'account_id' => factory('App\Account')->create()->id,
    ];
});
 
$factory->state(ReserveInfo::class, 'test_controller', function(Faker $faker) {
    return [
        'account_id' => $faker->unique()->randomNumber(5),
    ];
});