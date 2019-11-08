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
    			$providers = [];
    			for ($i=0; $i <= 3 ; $i++) { 
                    $provider_id = rand(0, 10);
                    do {
                        $provider_id = rand(0, 10);
    				    $providers[$i] = $provider_id;
                    } while(!in_array($provider_id, $providers));
    			}
    			$article->providers()->sync($providers);
    		}
    	}
    }
}
