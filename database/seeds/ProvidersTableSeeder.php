<?php

use Illuminate\Database\Seeder;
use App\Provider;
use App\Article;


class ProvidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$providers = [
    		'Buenos Aires',
    		'Rosario',
    		'Victoria',
    		'Galarza',
    		'Cordoba',
    		'Brazil',
    		'Tucuman',
    		'Santa Cruz',
    		'La Pampa',
    		'Gualeguay',
    	];

    	foreach ($providers as $provider) {
	        Provider::create([
	        	'name' => $provider,
	        	'user_id' => 2,
	        ]);
    	}

    	$articles = Article::all();
    	foreach ($articles as $article) {
    		if ($article->user_id == 2) {
                $amount = $article->stock;
                $cost = $article->cost;
                $price = $article->price;
                $providers = [];
                $amounts = [];
                $costs = [];
    			$prices = [];
    			for ($i=0; $i < 3 ; $i++) { 
                    // $amount = ceil($amount / 2);
                    if (isset($amounts[1])) {
                        $amounts[] = $amount - $amounts[0] - $amounts[1];
                        $costs[] = $cost - $costs[0] - $costs[1];
                        $prices[] = $price - $prices[0] - $prices[1];
                    } else
                    if (isset($amounts[0])) {
                        $amounts[] = rand(1, $amount - $amounts[0]);
                        $costs[] = rand(1, $cost - $costs[0]);
                        $prices[] = rand(1, $price - $prices[0]);
                    } else {
                        $amounts[] = rand(1, $amount - 5);
                        $prices[] = rand(1, $price - ($price / 2));
                        $costs[] = rand(1, $cost - ($cost / 2));
                    }

                    $provider_id = rand(0, 10);
                    while (in_array($provider_id, $providers)) {
                        $provider_id = rand(0, 10);
                    }
                    $providers[] = $provider_id;
    			}
                $article->providers()->attach([$providers[0],$providers[1],$providers[2]]);
    			// $article->providers()->attach([
       //              $providers[0] => [
       //                  'amount' => $amounts[0], 
       //                  'cost' => $costs[0], 
       //                  'price' => $prices[0], 
       //              ],
       //              $providers[1] => [
       //                  'amount' => $amounts[1], 
       //                  'cost' => $costs[1], 
       //                  'price' => $prices[1], 
       //              ],
       //              $providers[2] => [
       //                  'amount' => $amounts[2], 
       //                  'cost' => $costs[2], 
       //                  'price' => $prices[2], 
       //              ],
       //          ]);
    		}
    	}
    }
}
