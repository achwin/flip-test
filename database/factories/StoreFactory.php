<?php

use App\Models\Store;
use Faker\Generator as Faker;

$factory->define(Store::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'saldo' => $faker->numberBetween(1,1000),
        'account_name' => $faker->name,
        'account_number' => (string) $faker->numberBetween(100000,2000000),
        'bank_code' => 'bni',
    ];
});
