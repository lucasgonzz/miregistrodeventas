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

$bar_code = 1234567891000;
$factory->define(Article::class, function (Faker $faker) use ($bar_code) {
	$cost = rand(50, 3000);
    return [
    	'bar_code' => $bar_code,
        'name' => $faker->name,
        'cost' => $cost,
        'price' => rand($cost, $cost*2),
        'previus_price' => 0,
        'stock' => rand(6, 25),
        'user_id' => rand(1, 2),
    ];
});
