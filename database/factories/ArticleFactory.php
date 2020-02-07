<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use Faker\Generator as Faker;

// $bar_codes = [];
// $bar_code = 1;
// for ($i=0; $i < 60 ; $i++) { 
//     $bar_codes[] = $bar_code;
//     $bar_code++;
// }

$factory->define(Article::class, function (Faker $faker) {
	$cost = rand(50, 3000);
    $bar_code = rand(1000000000000, 9999999999999);
    $b_c = rand(1, 2);
    return [
    	'bar_code' => $b_c == 1 ? $bar_code : null,
        'name' => $faker->name,
        'cost' => 1000,
        'price' => 10000,
        // 'cost' => $cost,
        // 'price' => rand($cost, $cost*2),
        'previus_price' => 0,
        'stock' => rand(10, 25),
        'user_id' => rand(1, 2),
    ];
});
