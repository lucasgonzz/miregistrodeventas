<?php

use Illuminate\Database\Seeder;
use App\Article;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Article::class, 200)->create();
        Article::create([
        	'name' => 'Queso',
        	'cost' => 700,
        	'price' => 1000,
        	'uncontable' => 1,
        	'measurement' => 'kilo',
        	'user_id' => 2,
        ]);
        Article::create([
        	'name' => 'Cemento',
        	'cost' => 50,
        	'price' => 100,
        	'uncontable' => 1,
        	'measurement' => 'kilo',
        	'stock' => 50,
        	'user_id' => 2,
        ]);
        Article::create([
        	'name' => 'Caramelos',
        	'cost' => 30,
        	'price' => 50,
        	'uncontable' => 1,
        	'measurement' => 'gramo',
        	'stock' => 30,
        	'user_id' => 2,
        ]);
    }
}
