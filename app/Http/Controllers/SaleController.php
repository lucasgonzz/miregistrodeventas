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

    function getArticleOwnerId() {
        $user = Auth()->user();
        if (is_null($user->belongs_to)) {
            return $user->id;
        } else {
            return $user->belongs_to;
        }
    }

    function index() {
        $user = Auth()->user();
        if ($user->hasRole('provider')) {
            $sales = Sale::where('user_id', $this->getArticleOwnerId())
                            ->where('created_at', '>=', Carbon::today())
                            ->with('client')
                            ->with('articles')
                            ->orderBy('created_at', 'DESC')
                            ->get();
        } else {
            $sales = Sale::where('user_id', $this->getArticleOwnerId())
                            ->where('created_at', '>=', Carbon::today())
                            ->with('articles')
                            ->orderBy('created_at', 'DESC')
                            ->get();
        }
        return $sales;
    }

    /*
    |--------------------------------------------------------------------------
    | PreviusNext
    |--------------------------------------------------------------------------
    |
    |   * El parametro index indica el numero de dias a retroceder
    |   * Direction indica si se esta subiendo o bajando, se usa en el caso
    |   de que no haya ventas en tal fecha, si se esta bajando continua bajando
    |   y viceversa
    |   * only_one_date indica si se esta retrocediendo desde una fecha en especifico
    |   Si es nulo es porque se esta retrocediendo desde el principio
    |   Si no es nulo se empieza a retroceder desde la fecha que llega en esa variable
    |   
    */
    function previusNext($index, $direction, $only_one_date = null) {
        $user = Auth()->user();
        if (is_null($only_one_date)) {
            $carbon = Carbon::now('America/Argentina/Buenos_Aires');
        } else {
            $carbon = Carbon::create($only_one_date);
        }
        $sales = [];
        $date = $carbon->subDays($index);
        // echo "primera fecha: ".$date;

        // Se obtine la fecha de la primer compra para saber cuando dejar de buscar
        $limit_sale = Sale::where('user_id', $this->getArticleOwnerId())
                            ->orderBy('id', 'ASC')
                            ->first();
        $limit_date = $limit_sale->created_at;
        // echo " fecha limite: ".$limit_date;

        while (count($sales) == 0 && $date >= $limit_date) {
        // echo "entro";
        if ($user->hasRole('provider')) {
                $sales = Sale::where('user_id', $this->getArticleOwnerId())
                                    ->whereDate('created_at', $date)
                                    ->orderBy('id', 'DESC')
                                    ->with('client')
                                    ->with('articles')
                                    ->orderBy('created_at', 'DESC')
                                    ->get();
            } else {
                $sales = Sale::where('user_id', $this->getArticleOwnerId())
                                    ->whereDate('created_at', $date)
                                    ->orderBy('id', 'DESC')
                                    ->with('articles')
                                    ->orderBy('created_at', 'DESC')
                                    ->get();
            }
            if (count($sales) == 0) {
                // echo "No tenia en: " . $index;
                if ($index != 0) {
                    if ($direction == 'previus') {
                        $index++;
                    } else {
                        $index--;
                    }
                }
                if (is_null($only_one_date)) {
                    $carbon = Carbon::now('America/Argentina/Buenos_Aires');
                } else {
                    $carbon = Carbon::create($only_one_date);
                }
                if ($index == 0) {
                    $date = date('Y-m-d');
                } else {
                    $date = $carbon->subDays($index);
                }
            }
        }
        return [
            'index' => $index,
            'sales' => $sales
        ];
    }

    function onlyOneDate($date) {
        $user = Auth()->user();
        if ($user->hasRole('provider')) {
            $sales = Sale::where('user_id', $this->getArticleOwnerId())
                    ->whereDate('created_at', $date)
                    ->with('articles')
                    ->with('client')
                    ->orderBy('created_at', 'DESC')
                    ->get();
        } else {
            $sales = Sale::where('user_id', $this->getArticleOwnerId())
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
            $sales = Sale::where('user_id', $this->getArticleOwnerId())
                    ->whereBetween('created_at', [$from, $to])
                    ->with('articles')
                    ->with('client')
                    ->orderBy('created_at', 'DESC')
                    ->get();
        } else {
            $sales = Sale::where('user_id', $this->getArticleOwnerId())
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
            foreach ($sale->articles as $article) {
                if ($article->uncontable == 0) {
                    $article->stock += $article->pivot->amount;
                } else {
                    if ($article->measurement != $article->pivot->measurement) {
                        $article->stock += $article->pivot->amount / 1000;
                    } else {
                        $article->stock += $article->pivot->amount;
                    }
                }
                $article->save();
            }
            $sale->delete();
        }
    }

    function delete($id) {
        $sale = Sale::find($id);
        foreach ($sale->articles as $article) {
            if ($article->uncontable == 0) {
                $article->stock += $article->pivot->amount;
            } else {
                if ($article->measurement != $article->pivot->measurement) {
                    $article->stock += $article->pivot->amount / 1000;
                } else {
                    $article->stock += $article->pivot->amount;
                }
            }
            $article->save();
        }
        $sale->delete();
    }

    function store(Request $request) {
        $with_card = (bool)$request->with_card;
        $user = Auth()->user();
        $last_sale = Sale::where('user_id', $this->getArticleOwnerId())
                            ->orderby('created_at','DESC')
                            ->first();
        if ($last_sale === null) {
            if ($user->hasRole('provider')) {
            	$sale = Sale::create([
            		'user_id' => $this->getArticleOwnerId(),
                    'num_sale' => 1,
                    'percentage_card' => $with_card ? $user->percentage_card : null,
            		'client_id' => $request->client_id
            	]);
            } else {
                $sale = Sale::create([
                    'user_id' => $this->getArticleOwnerId(),
                    'percentage_card' => $with_card ? $user->percentage_card : null,
                    'num_sale' => 1,
                ]);
            }
        } else {
            if ($user->hasRole('provider')) {
                $num_sale = $last_sale->num_sale;
                $num_sale++;
                $sale = Sale::create([
                    'user_id' => $this->getArticleOwnerId(),
                    'num_sale' => $num_sale,
                    'percentage_card' => $with_card ? $user->percentage_card : null,
                    'client_id' => $request->client_id
                ]);
            } else {
                $num_sale = $last_sale->num_sale;
                $num_sale++;
                $sale = Sale::create([
                    'user_id' => $this->getArticleOwnerId(),
                    'percentage_card' => $with_card ? $user->percentage_card : null,
                    'num_sale' => $num_sale,
                ]);
            }
        }

    	foreach ($request->articles as $article) {
            $price = 0;
            if (!is_null($article['offer_price'])) {
                $price = $article['offer_price'];
            } else {
                $price = $article['price'];
            }
            $sale->articles()->attach($article['id'], [
                                                        'amount' => $article['amount'],
                                                        'measurement' => 
                                                                        isset($article['measurement']) 
                                                                        ? $article['measurement'] 
                                                                        : null,
                                                        'cost' => (float)$article['cost'],
                                                        'price' => (float)$price,
                                                    ]);
            $article_ = Article::find($article['id']);
    		if (!is_null($article_->stock)) {
                if ($article_->uncontable == 1) {
                    if ($article['measurement'] != $article['measurement_original']) {
                        $article_->stock -= ((float)$article['amount'] / 1000);
                    } else {
                        $article_->stock -= (float)$article['amount'];
                    }
                } else {
                    $article_->stock -= $article['amount'];
                }
                $article_->timestamps = false;
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
