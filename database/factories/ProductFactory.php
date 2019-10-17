<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 500)
    ];
});

$factory->defineAs(App\Product::class, 'reFormatted',function (Faker $faker) {
    return [
        'data' => [
            'type' => 'products',
            'attributes' => [
                'name' => $faker->name,
                'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 500)
            ]
        ]
    ];
});

$factory->defineAs(App\Product::class, 'woutName', function (Faker $faker) {
    return [
        'data' => [
            'type' => 'products',
            'attributes' => [
                'name' => '',
                'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 500)
            ]
        ]
    ];
});

$factory->defineAs(App\Product::class, 'woutPrice', function (Faker $faker) {
    return [
        'data' => [
            'type' => 'products',
            'attributes' => [
                'name' => $faker->name,
                'price' => null
            ]
        ]
    ];
});

$factory->defineAs(App\Product::class, 'woutNumPrice', function (Faker $faker) {
    return [
        'data' => [
            'type' => 'products',
            'attributes' => [
                'name' => $faker->name,
                'price' => $faker->word
            ]
        ]
    ];
});

$factory->defineAs(App\Product::class, 'subZero',function (Faker $faker) {
    return [
        'data' => [
            'type' => 'products',
            'attributes' => [
                'name' => $faker->name,
                'price' => $faker->randomFloat($nbMaxDecimals = 2, $min =-500, $max = 0)
            ]
        ]
    ];
});
