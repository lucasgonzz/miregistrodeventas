<?php

namespace App\Imports;

use App\Article;
// use Maatwebsite\Excel\Concerns\Importable;
// use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow};

class ArticlesImport implements ToModel, WithHeadingRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        return new Article([
            'bar_code' => $row['codigo'],
            'name' => $row['nombre'],
            'cost' => $row['costo'],
            'price' => $row['precio'],
            'previus_price' => $row['precio_anterior'],
            'stock' => $row['stock'],
            'user_id' => Auth()->user()->id,
        ]);
    }
}
