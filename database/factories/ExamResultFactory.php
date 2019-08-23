<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\ExamResult;
use Faker\Generator as Faker;

$factory->define(ExamResult::class, function (Faker $faker) {
    return [
        // 'id'=>$faker->unique()->numberBetween(1,10),
        // 'reserve_info_id'=>$faker->unique()->numberBetween(31,40),
        'findings_chestabdomen'=>$faker->randomElement(['所見なし','所見あり']), 
        'height'=>$faker->numberBetween(150,180), 
        'weight'=>$faker->numberBetween(45,90), 
        'bodyfat_ratio'=>$faker->numberBetween(21,29),
        'abdominal_circumference'=>$faker->numberBetween(77,100),
        'r_eyesight'=>$faker->randomFloat(1,0.1,1.5),
        'l_eyesight'=>$faker->randomFloat(1,0.1,1.5),
        'corrected_r_eyesight'=>$faker->randomFloat(1,0.1,1.5),
        'corrected_l_eyesight'=>$faker->randomFloat(1,0.1,1.5),
        'r_hearing_1000hz'=>$faker->randomElement(['所見なし','所見あり']), 
        'l_hearing_1000hz'=>$faker->randomElement(['所見なし','所見あり']), 
        'r_hearing_4000hz'=>$faker->randomElement(['所見なし','所見あり']), 
        'l_hearing_4000hz'=>$faker->randomElement(['所見なし','所見あり']), 
        'hearing_on_conv'=>$faker->randomElement(['所見なし','所見あり']), 
        'h_blood_pressure'=>$faker->numberBetween(110,150), 
        'l_blood_pressure'=>$faker->numberBetween(60,95),  
        'urinary_protein'=>$faker->randomElement(['-','±','+']), 
        'urinary_sugar'=>$faker->randomElement(['-','+']), 
        'urinary_urobilinogen'=>$faker->randomElement(['-','±','+']), 
        'urinary_ph'=>$faker->randomFloat(1,6.1,7.5),
        'urinary_blood'=>$faker->randomElement(['-','+']), 
        'eye_pressure_r'=>$faker->numberBetween(5,25), 
        'eye_pressure_l'=>$faker->numberBetween(5,25), 
        'lung_capacity'=>$faker->randomFloat(2,2.00,4.00), 
        'lung_fev1_sec'=>$faker->randomFloat(2,2.00,4.00), 
        'lung_fev1_per'=>$faker->numberBetween(60,90),
        'grip_strength_r'=>$faker->numberBetween(15,60), 
        'grip_strength_l'=>$faker->numberBetween(15,60), 
        'is_hungry'=>$faker->boolean(), 
        'hours_after_meals'=>$faker->numberBetween(0,10), 
        'chest_xray_no'=>$faker->unique()->numberBetween(101,200),
        'stomach_xray_no'=>$faker->unique()->numberBetween(101,200),
        'electro_no'=>$faker->unique()->numberBetween(101,200),
        'eyeground_no'=>$faker->unique()->numberBetween(101,200),
    ];
});
