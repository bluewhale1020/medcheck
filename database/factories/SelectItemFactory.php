<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\SelectItem;
use Faker\Generator as Faker;

$factory->define(SelectItem::class, function (Faker $faker) {
    return [
        // 'id'=>$faker->unique()->numberBetween(1,10),
        // 'reserve_info_id'=>$faker->unique()->numberBetween(31,40),
        'height'=>$faker->numberBetween(0,2), 
        'weight'=>$faker->numberBetween(0,2), 
        'bodyfat_ratio'=>$faker->numberBetween(0,2),
        'abdominal_circumference'=>$faker->numberBetween(0,2),
        'vision_test'=>$faker->numberBetween(0,2),
        'hearing_test'=>$faker->numberBetween(0,2),
        'hearing_test_conv'=>$faker->numberBetween(0,2),
        'physical_examination'=>$faker->numberBetween(0,2),
        'blood_pressure'=>$faker->numberBetween(0,2),
        'urinary_test'=>$faker->numberBetween(0,2),
        'urinary_test_type'=>$faker->randomElement(['糖+蛋白+潜血','糖+蛋白']),
        'urinary_sediment'=>$faker->numberBetween(0,2),
        'blood_test'=>$faker->numberBetween(0,2),
        'blood_test_type'=>$faker->randomElement(['E+F','A+B+C']),
        'fecaloccult_blood'=>$faker->numberBetween(0,2),
        'electrogram_test'=>$faker->numberBetween(0,2),
        'chest_xray'=>$faker->numberBetween(0,2),
        'stomach_xray'=>$faker->numberBetween(0,2),
        'eye_pressure'=>$faker->numberBetween(0,2), 
        'eyeground'=>$faker->numberBetween(0,2),
        'grip_strength'=>$faker->numberBetween(0,2),
        'lung_capacities'=>$faker->numberBetween(0,2),
        'urinary_metabolites'=>$faker->numberBetween(0,2),
        'methyl_hippuric_acid'=>$faker->numberBetween(0,2),
        'n-formylmethylamine'=>$faker->numberBetween(0,2),
        'mandelic_acid'=>$faker->numberBetween(0,2),
        'trichloroacetic_acid'=>$faker->numberBetween(0,2),
        'hippuric_acid'=>$faker->numberBetween(0,2),
        '2,5-hexanedione'=>$faker->numberBetween(0,2),
        'formaldehyde'=>$faker->numberBetween(0,2),
        'dust'=>$faker->numberBetween(0,2),
        'lead'=>$faker->numberBetween(0,2),
        'ionizing_radiation'=>$faker->numberBetween(0,2),
        'Indium'=>$faker->numberBetween(0,2), 
        

    ];
});
