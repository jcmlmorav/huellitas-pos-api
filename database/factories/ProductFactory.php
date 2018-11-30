<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'barcode' => $faker->randomNumber(5),
        'description' => $faker->text(30),
        'quantity' => $faker->numberBetween(1, 5),
        'price' => $faker->randomFloat(2, 1000, 500000),
        'discount' => $faker->numberBetween(0, 20)
    ];
});
