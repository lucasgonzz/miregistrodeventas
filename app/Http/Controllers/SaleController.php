<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Article;

class SaleController extends Controller
{

    function store(Request $request) {
    	$sale = Sale::create([
    		'user_id' => Auth()->user()->id,
    		'client_id' => $request->client_id
    	]);
    	foreach ($request->articles as $article) {
    		$sale->articles()->attach($article['id'], ['amount' => $article['amount']]);
    		$article_ = Article::find($article['id']);
    		if (!is_null($article_->stock)) {
	    		$article_->stock -= $article['amount'];
	    		$article_->save();
    		}
    	}
        return $sale;
    }
}
