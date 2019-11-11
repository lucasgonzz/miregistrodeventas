<?php

namespace App\Exports;

use App\Article;
use Maatwebsite\Excel\Concerns\FromCollection;

class ArticlesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Article::where('user_id', Auth()->user()->id)->select('bar_code', 'name', 'cost', 'price', 'previus_price', 'stock', 'created_at', 'updated_at')->get();
    }
}
