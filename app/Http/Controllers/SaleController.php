<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Article;
use Carbon\Carbon;

// Helpers
use App\Http\Controllers\Helpers\PdfPrintSale;
use App\Http\Controllers\Helpers\PdfPrintArticle;

class SaleController extends Controller
{

    function index() {
        $user = Auth()->user();
        if ($user->hasRole('provider')) {
            $sales = Sale::where('user_id', $user->id)
                            ->where('created_at', '>=', Carbon::today())
                            ->with('client')
                            ->with('articles')
                            ->orderBy('created_at', 'DESC')
                            ->get();
        } else {
            $sales = Sale::where('user_id', $user->id)
                            ->where('created_at', '>=', Carbon::today())
                            ->with('articles')
                            ->orderBy('created_at', 'DESC')
                            ->get();
        }
        return $sales;
    }

    function previusNext($index, $only_one_date = null) {
        $user = Auth()->user();
        if (is_null($only_one_date)) {
            $carbon = Carbon::now('America/Argentina/Buenos_Aires');
        } else {
            $carbon = Carbon::create($only_one_date);
        }
        $date = $carbon->subDays($index);
        if ($user->hasRole('provider')) {
            $sales = Sale::where('user_id', Auth()->user()->id)
                                ->whereDate('created_at', $date)
                                ->orderBy('id', 'DESC')
                                ->with('client')
                                ->with('articles')
                                ->orderBy('created_at', 'DESC')
                                ->get();
        } else {
            $sales = Sale::where('user_id', Auth()->user()->id)
                                ->whereDate('created_at', $date)
                                ->orderBy('id', 'DESC')
                                ->with('articles')
                                ->orderBy('created_at', 'DESC')
                                ->get();
        }
        return $sales;
    }

    function onlyOneDate($date) {
        $user = Auth()->user();
        if ($user->hasRole('provider')) {
            $sales = Sale::where('user_id', $user->id)
                    ->whereDate('created_at', $date)
                    ->with('articles')
                    ->with('client')
                    ->orderBy('created_at', 'DESC')
                    ->get();
        } else {
            $sales = Sale::where('user_id', $user->id)
                    ->whereDate('created_at', $date)
                    ->with('articles')
                    ->orderBy('created_at', 'DESC')
                    ->get();            
        }
        return $sales;
    }

    function fromDate($from, $to, $last_day_inclusive) {
        $user = Auth()->user();
        $last_day_inclusive = (bool)$last_day_inclusive;
        if ($last_day_inclusive) {
            $to = new Carbon($to);
            $to->addDay();
        }
        if ($user->hasRole('provider')) {
            $sales = Sale::where('user_id', $user->id)
                    ->whereBetween('created_at', [$from, $to])
                    ->with('articles')
                    ->with('client')
                    ->orderBy('created_at', 'DESC')
                    ->get();
        } else {
            $sales = Sale::where('user_id', $user->id)
                    ->whereBetween('created_at', [$from, $to])
                    ->with('articles')
                    ->orderBy('created_at', 'DESC')
                    ->get();            
        }
        return $sales;
    }

    function deleteSales($sales_id) {
        foreach (explode('-', $sales_id) as $sale_id) {
            $sale = Sale::find($sale_id);
            $sale->delete();
        }
    }

    function delete($id) {
        $sale = Sale::find($id);
        $sale->delete();
    }

    function store(Request $request) {
        $user = Auth()->user();
        $last_sale = Sale::where('user_id', $user->id)
                            ->orderby('created_at','DESC')
                            ->first();
        if ($last_sale === null) {
            if ($user->hasRole('provider')) {
            	$sale = Sale::create([
            		'user_id' => $user->id,
                    'num_sale' => 1,
            		'client_id' => $request->client_id
            	]);
            } else {
                $sale = Sale::create([
                    'user_id' => $user->id,
                    'num_sale' => 1,
                ]);
            }
        } else {
            if ($user->hasRole('provider')) {
                $num_sale = $last_sale->num_sale;
                $num_sale++;
                $sale = Sale::create([
                    'user_id' => $user->id,
                    'num_sale' => $num_sale,
                    'client_id' => $request->client_id
                ]);
            } else {
                $num_sale = $last_sale->num_sale;
                $num_sale++;
                $sale = Sale::create([
                    'user_id' => $user->id,
                    'num_sale' => $num_sale,
                ]);
            }
        }

    	foreach ($request->articles as $article) {
    		$sale->articles()->attach($article['id'], [
                                                        'amount' => $article['amount'],
                                                        'cost' => $article['cost'],
                                                        'price' => $article['price'],
                                                    ]);
    		$article_ = Article::find($article['id']);
    		if (!is_null($article_->stock)) {
	    		$article_->stock -= $article['amount'];
	    		$article_->save();
    		}
    	}
        $sale = Sale::where('id', $sale->id)->with('articles')->first();
        return $sale;
    }

    function pdf($sales_id, $company_name, $articles_cost, $articles_subtotal_cost, $articles_total_price, 
                            $articles_total_cost, $borders) {
        $sales_id = explode('-', $sales_id);
        $pdf = new PdfPrintSale(
                                    $sales_id, 
                                    (bool)$company_name, 
                                    (bool)$articles_cost, 
                                    (bool)$articles_subtotal_cost, 
                                    (bool)$articles_total_price, 
                                    (bool)$articles_total_cost, 
                                    (bool)$borders
                                );
        $pdf->printSales();
        // $print_article = new PdfPrintArticle();
        
    }
    
}
