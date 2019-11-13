<?php

use Illuminate\Database\Seeder;
use App\Sale;
use App\Article;

class SalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sale = Sale::create([
        	'user_id' => 1,
        	'client_id' => 1,
        ]);

        $articles = Article::where('user_id', 1)->get();

        foreach ($articles as $article) {
        	$sale->articles()->attach($article->id, ['amount' => 5]);
        }
    }
}
