<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Article;
use App\BarCode;

use App\Exports\ArticlesExport;
use App\Imports\ArticlesImport;
use Maatwebsite\Excel\Facades\Excel;

class ArticleController extends Controller
{
    function index() {
        $user = Auth()->user();
        if ($user->hasRole('commerce')) {
        	$articles = Article::where('user_id',$user->id)
                                ->orderBy('id', 'DESC')
                                ->with(array('providers' => function($q) {
                                    $q->orderBy('created_at', 'DESC');
                                }))
                                ->paginate(10);
        } else {
            $articles = Article::where('user_id',$user->id)
                                ->orderBy('id', 'DESC')
                                ->paginate(10);
        }
    	return [
                'pagination' => [
                    'total' => $articles->total(),
                    'current_page' => $articles->currentPage(),
                    'per_page' => $articles->perPage(),
                    'last_page' => $articles->lastPage(),
                    'from' => $articles->firstItem(),
                    'to' => $articles->lastPage(),
                ],
                'articles' => $articles 
            ];
    }

    function getByBarCode($bar_code) {
        $user = Auth()->user();
        if ($user->hasRole('commerce')) {
            return Article::where('user_id', $user->id)
                            ->where('bar_code', $bar_code)
                            ->with('providers')
                            ->first();
        } else {
            return Article::where('user_id', $user->id)
                            ->where('bar_code', $bar_code)
                            ->first();
        }
    }

    function getByName($name) {
        $user = Auth()->user();
        if ($user->hasRole('commerce')) {
            return Article::where('user_id', $user->id)
                            ->where('name', $name)
                            ->with('providers')
                            ->first();
        } else {
            return Article::where('user_id', $user->id)
                            ->where('name', $name)
                            ->first();
        }
        // if ($article === null) {
        //     return null;
        // } else {
        //     return $article;
        // }
    }

    function getBarCodes() {
        return Article::where('user_id', Auth()->user()->id)
                        ->pluck('bar_code');
    }

    function getNames() {
        return Article::where('user_id', Auth()->user()->id)
                        // ->whereNull('bar_code')
                        ->pluck('name');
    }

    function search($query) {
        $user = Auth()->user();
        $articles = Article::where('user_id', Auth()->user()->id)
                            ->where('bar_code', $query);
        if ($user->hasRole('commerce')) {
            $articles = $articles->with('providers');
        }
        $articles = $articles->get();

        if (count($articles) == 0) {
            $articles = Article::where('user_id', Auth()->user()->id)
                                ->where('name', 'LIKE', "%$query%");
            if ($user->hasRole('commerce')) {
                $articles = $articles->with('providers');
            }
            $articles = $articles->get();
        } 
        return $articles;
    }

    function pre_search($query) {
        return Article::where('user_id', Auth()->user()->id)
                            ->where('name', 'LIKE', "%$query%")->get();
    }

    function previusNext($index) {
        $user = Auth()->user();
        if ($user->hasRole('commerce')) {
            $articles = Article::where('user_id', Auth()->user()->id)
                                ->orderBy('id', 'DESC')
                                ->with('providers')
                                ->take($index)
                                ->get();
        } else {
            $articles = Article::where('user_id', Auth()->user()->id)
                                ->orderBy('id', 'DESC')
                                ->take($index)
                                ->get();
        }
        return $articles[$index-1];
    }

    function update(Request $request, $id) {
        $article = Article::find($id);
        $updated_article = $request->article;

        if ($article->price != $updated_article['price']) {
            $article->previus_price = $article->price;
        }
        if (!$updated_article['act_fecha']) {
            $article->timestamps = false;
        } 
        $article->name =  ucwords($updated_article['name']);
        $article->cost = $updated_article['cost'];
        $article->price = $updated_article['price'];
        $article->stock += (int)$updated_article['new_stock'];
        $article->save();
        if (Auth()->user()->hasRole('commerce')) {
            $article->providers()
                                ->attach(
                                    $updated_article['provider'], 
                                    [
                                        'amount' => $updated_article['new_stock'],
                                        'cost' => $updated_article['cost'],
                                        'price' => $updated_article['price'],
                                    ]);
        }
        return 'bien';
    }

    function updateByPorcentage(Request $request) {
        // return $request->decimals;
        // if ($request->round == 'up') {
        //     $round = PHP_ROUND_HALF_UP;
        // } else if ($request->round == 'down') {
        //     $round = PHP_ROUND_HALF_DOWN;
        // }
        $decimals = (bool)$request->decimals;
        $articles_ids = $request->articles_ids;
        foreach ($articles_ids as $article_id) {
            $article = Article::find($article_id);
            if (!empty($request->cost)) {
                if($decimals) {
                    $article->cost += round(($request->cost/100)*$article->cost, 2);
                } else {
                    $article->cost += round(($request->cost/100)*$article->cost, 0);
                }
            }
            if (!empty($request->price)) {
                $article->previus_price = $article->price;
                if($decimals) {
                    $article->price += round(($request->price/100)*$article->price, 2);
                } else {
                    $article->price += round(($request->price/100)*$article->price, 0);
                }
            }
            $article->save();
        }
    }

    function store(Request $request) {
        
        $user = Auth()->user();
        $article = new Article();
        $article->bar_code = $request->article['bar_code'];
        $article->name = ucwords($request->article['name']);
        $article->cost = $request->article['cost'];
        $article->price = $request->article['price'];
        $article->previus_price = 0;
        $article->stock = $request->article['stock'];
        $article->user_id = $user->id;

        $date = date('Y-m-d H:i:s');
        // if ($request->article['created_at'] != $date) {
        //     $article->created_at = $request->article['created_at'];
        //     $article->updated_at = $request->article['created_at'];
        // }

        $article->save();
        if ($user->hasRole('commerce')) {
            $article->providers()->attach($request->article['provider'], [
                                            'amount' => $request->article['stock'],
                                            'cost' => $request->article['cost'],
                                            'price' => $request->article['price']
                                        ]);
        }

        $bar_code = BarCode::where('user_id', $user->id)
                                ->where('name', $request->article['bar_code'])
                                ->first();
        if ($bar_code === null) {
            // return 'No existe';
        } else {
            $bar_code->article_id = $article->id;
            $bar_code->save();
            // return 'ASD';
        }
        return $article;
    }


    function destroy($id) {
        Article::find($id)->delete();
    }

    function filter(Request $request) {
        $user = Auth()->user();
        $mostrar = $request->mostrar;
        $ordenar = $request->ordenar;
        $precio_entre = $request->precio_entre;

        $articles = Article::where('user_id', $user->id);

        // Mostrar
        if ($mostrar == 'desactualizados') {
                $fecha_actual = date('d-m-Y');
                $hace_6_meses = date('d-m-Y', strtotime($fecha_actual."- 6 month"));
                $articles = $articles->whereDate('updated_at', '<=', $hace_6_meses);
        }
        if ($mostrar == 'no-vendidos') {
            $articles = $articles->doesntHave('sales');
        }
        if ($mostrar == 'no-stock') {
            $articles = $articles->where('stock', 0);
        }

        if ($user->hasRole('commerce')) {
            $providers = $request->providers;
            if (count($providers) == 1) {
                $articles = $articles->with('providers')->whereHas('providers', function(Builder $query) use ($providers) {
                    $query->where('name', $providers[0]);
                });
            } else {
                $articles = $articles->with('providers')->whereHas('providers', function(Builder $query) use ($providers) {
                    $query->whereIn('name', $providers);
                });
            }
        }

        // Ordenar
        if ($ordenar == 'nuevos-viejos') {
            $articles = $articles->orderBy('id', 'DESC');
        }
        if ($ordenar == 'viejos-nuevos') {
            $articles = $articles->orderBy('id', 'ASC');
        }
        if ($ordenar == 'caros-baratos') {
            $articles = $articles->orderBy('price', 'DESC');
        }
        if ($ordenar == 'baratos-caros') {
            $articles = $articles->orderBy('price', 'ASC');
        }
        return $articles->get();
    }

    function export() {
        return Excel::download(new ArticlesExport, 'miregistrodeventas-articulos.xlsx');
    }

    function import(Request $request) {
        (new ArticlesImport)->import($request->exel);
        // Excel::import(new ArticlesImport, $request->exel);
        if (Auth()->user()->hasRole('provider')) {
            return redirect()->route('listado.provider')->with('success', 'Importacion realizada con exito');
        } else {
            return redirect()->route('listado.commerce')->with('success', 'Importacion realizada con exito');
        }
    }
}
