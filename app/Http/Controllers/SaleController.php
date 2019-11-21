<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Article;

class SaleController extends Controller
{

    function store(Request $request) {
        $user = Auth()->user();
        if ($user->hasRole('provider')) {
        	$sale = Sale::create([
        		'user_id' => $user->id,
        		'client_id' => $request->client_id
        	]);
        } else {
            $sale = Sale::create([
                'user_id' => $user->id,
            ]);
        }
    	foreach ($request->articles as $article) {
    		$sale->articles()->attach($article['id'], ['amount' => $article['amount']]);
    		$article_ = Article::find($article['id']);
    		if (!is_null($article_->stock)) {
	    		$article_->stock -= $article['amount'];
	    		$article_->save();
    		}
    	}
        $sale = Sale::where('id', $sale->id)->with('articles')->first();
        return $sale;
    }
}
