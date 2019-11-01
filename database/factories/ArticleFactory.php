<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    $bar_code = 0;
	$cost = rand(50, 3000);
    $bar_code++;
    return [
    	'bar_code' => $bar_code,
        'name' => $faker->name,
        'cost' => $cost,
        'price' => rand($cost, $cost*2),
        'stock' => rand(6, 25),
        'user_id' => rand(1, 2),
    ];
});
