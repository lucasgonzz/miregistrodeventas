<?php

use Illuminate\Database\Seeder;
use App\Sale;
use App\Article;
use Carbon\Carbon;

class SalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $num_sale = 1;
        $sale = Sale::create([
            'user_id' => 1,
            'num_sale' => $num_sale,
            'client_id' => 1,
        ]);

        $articles = Article::where('user_id', 1)->get();

        foreach ($articles as $article) {
            $sale->articles()->attach($article->id, ['amount' => 5]);
        }

        $sale = Sale::create([
            'user_id' => 2,
            'num_sale' => $num_sale,
        ]);

        $articles = Article::where('user_id', 2)
                                ->inRandomOrder()
                                ->limit(7)->get();

        foreach ($articles as $article) {
            $sale->articles()->attach($article->id, [
                                                        'amount' => 1,
                                                        'cost' => $article->cost,
                                                        'price' => $article->price,
                                                    ]);
        }

        for ($i=1; $i <= 6; $i++) {  
            for ($j=0; $j < 7; $j++) { 
                $num_sale++;
                $sale = Sale::create([
                    'user_id' => 2,
                    'num_sale' => $num_sale,
                    'created_at' => Carbon::now()->subDay($i)
                ]);
                $articles = Article::where('user_id', 2)
                                        ->inRandomOrder()
                                        ->limit(50)->get();
                foreach ($articles as $article) {
                    $sale->articles()->attach($article->id, [
                                                                'amount' => rand(1,4),
                                                                'cost' => $article->cost,
                                                                'price' => $article->price,
                                                            ]);
                }
            }
        }
    }
}
