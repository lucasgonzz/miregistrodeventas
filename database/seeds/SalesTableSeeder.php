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
        $num_sale = 0;
        $num_sale++;
        $sale = Sale::create([
            'user_id' => 1,
            'num_sale' => $num_sale,
            'client_id' => 1,
            'created_at' => Carbon::now()->subDay(10)
        ]);
        // $i son los dias 
        // $j son la cantidad de ventas por dia
        for ($i=6; $i >= 0; $i--) {  
            if ($i == 2) {
                continue;
            } else {
                for ($j=0; $j < 7; $j++) { 
                    $num_sale++;
                    $n = rand(0, 1);
                    $sale = Sale::create([
                        'user_id' => 1,
                        'num_sale' => $num_sale,
                        'percentage_card' => $n == 0 ? 50 : null,
                        'client_id' => 1,
                        'created_at' => $i == 0 ? date('Y-m-d') : Carbon::now()->subDay($i)
                    ]);
                    $articles = Article::where('user_id', 1)
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

        $num_sale = 0;
        for ($i=6; $i >= 0; $i--) {  
            if ($i == 2) {
                continue;
            } else {
                $hora = 9;
                $minutos = 0;
                for ($j=0; $j < 7; $j++) { 
                    $hora++;
                    if ($minutos == 60) {
                        $minutos = 0;
                    }
                    $minutos += 10;
                    $num_sale++;
                    $n = rand(0, 1);
                    $sale = Sale::create([
                        'user_id' => 2,
                        'num_sale' => $num_sale,
                        'percentage_card' => $n == 0 ? 50 : null,
                        'created_at' => $i == 0 ? date('Y-m-d') . ' ' . "$hora:$minutos:00" : Carbon::now()->subDay($i)
                    ]); 
                    $articles = Article::where('user_id', 2)
                                            ->inRandomOrder()
                                            ->limit(rand(4, 10))->get();
                    foreach ($articles as $article) {
                        $sale->articles()->attach($article->id, [
                                                                    'amount' => rand(1,2),
                                                                    'cost' => $article->cost,
                                                                    'price' => $article->price,
                                                                ]);
                    }
                }
            }
        }
    }
}
