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
        for ($i=1; $i < 3 ; $i++) { 
            Article::create([
            	'name' => 'Queso',
            	'cost' => 700,
            	'price' => 1000,
            	'uncontable' => 1,
            	'measurement' => 'kilo',
            	'user_id' => $i,
            ]);
            Article::create([
            	'name' => 'Cemento',
            	'cost' => 50,
            	'price' => 100,
            	'uncontable' => 1,
            	'measurement' => 'kilo',
            	'stock' => 50,
            	'user_id' => $i,
            ]);
            Article::create([
            	'name' => 'Caramelos',
            	'cost' => 30,
            	'price' => 50,
            	'uncontable' => 1,
            	'measurement' => 'gramo',
            	'stock' => 30,
            	'user_id' => $i,
            ]);
            Article::create([
                'name' => 'Mesa de madera',
                'cost' => 5000,
                'price' => 7000,
                'uncontable' => 0,
                'stock' => 6,
                'user_id' => $i,
            ]);
            Article::create([
                'bar_code' => '1752364895213',
                'name' => 'Parlante jbl',
                'cost' => 10000,
                'price' => 13000,
                'uncontable' => 0,
                'stock' => 12,
                'user_id' => $i,
            ]);
            Article::create([
                'bar_code' => '1752364005213',
                'name' => 'Televisor',
                'cost' => 15000,
                'price' => 20000,
                'uncontable' => 0,
                'stock' => 10,
                'user_id' => $i,
            ]);
            Article::create([
                'name' => 'Comida para perro pedigree',
                'cost' => 200,
                'price' => 400,
                'uncontable' => 1,
                'measurement' => 'kilo',
                'stock' => 20,
                'user_id' => $i,
            ]);
        }

        for ($i=0; $i < 1; $i++) { 
            Article::create([
                'bar_code' => '170101000213',
                'name' => 'Auto a control remoto',
                'cost' => 500,
                'price' => 700,
                'uncontable' => 0,
                'stock' => 30,
                'user_id' => 2,
            ]);
        }
    }
}
