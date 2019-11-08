<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
require('fpdf/fpdf.php');
use fpdf;
use App\Article;

class PdfController extends Controller
{
    function articles($columns_string, $idsArticles) {

    	$articles = [];
    	$ids_articles = explode('-', $idsArticles);
    	$columns = explode('-', $columns_string);

    	// dd($columns);

    	foreach ($ids_articles as $id_article) {
    		$articles[] = Article::find($id_article);
    	}

		$pdf = new Pdf($columns);
		$pdf->AliasNbPages();
		$pdf->addPage();
		$pdf->setFont('Times', '', 14);
		$pdf->setMargins(10, 0);
		foreach ($articles as $article) {
			if (in_array('bar_code', $columns)) {
				$pdf->Cell(35,10, $article->bar_code, 1);
			}
			if (in_array('name', $columns)) {
				$name = $article->name;
				if (strlen($name) > 19) {
					$name = substr($name, 0, 19) . '..';
					$pdf->Cell(50,10,$name, 1);
				} else {
					$pdf->Cell(50,10,$name, 1);
				}
			}
			if (in_array('cost', $columns)) {
				$cost = $article->cost;
				$centavos = explode('.', $cost)[1];
				if ($centavos == '00') {
					$cost = explode('.', $cost)[0];
					$cost = number_format((int)$cost, 0, '', '.');
				} else {
					$cost = number_format((int)$cost, 2, ',', '.');
				}
				$pdf->Cell(20,10, $cost, 1);
			}
			if (in_array('price', $columns)) {
				$pdf->Cell(20,10, $article->price, 1);
			}
			if (in_array('last_price', $columns)) {
				$pdf->Cell(20,10, $article->last_price, 1);
			}
			if (in_array('stock', $columns)) {
				$pdf->Cell(15,10, $article->stock, 1);
			}
			if (in_array('created_at', $columns)) {
				$pdf->Cell(25,10,date_format($article->created_at, 'd/m/y'),1);
			}
			if (in_array('updated_at', $columns)) {
				$pdf->Cell(25,10,date_format($article->updated_at, 'd/m/y'),1);
			}

			// $pdf->SetY(10);
			// $pdf->Cell(20,10,$article->cost,1);
			// $pdf->Cell(20,10,$article->price,1);
			// $pdf->Cell(15,10,$article->stock,1);
			$pdf->Ln();
		}

		$pdf->Output();
		exit;
    }
}


class Pdf extends fpdf {

	function __construct($columns) {
		parent::__construct();
		$this->columns = $columns;
	}

	function Header() {
		$this->setFont('Arial', 'B', 12);
		$this->setY(10);
		// $this->setLineWidth(1);
		// $this->SetFillColor(200,220,255);
		// $this->Cell(80);
		// dd($this->columns);
		if (in_array('bar_code', $this->columns)) {
			$this->Cell(35, 10, 'Codigo',1,0,'C');
		}
		if (in_array('name', $this->columns)) {
			$this->Cell(50, 10, 'Nombre',1,0,'C');
		}
		if (in_array('cost', $this->columns)) {
			$this->Cell(20, 10, 'Costo',1,0,'C');
		}
		if (in_array('price', $this->columns)) {
			$this->Cell(20, 10, 'Precio',1,0,'C');
		}
		if (in_array('last_price', $this->columns)) {
		}
		if (in_array('stock', $this->columns)) {
			$this->Cell(15, 10, 'Stock',1,0,'C');
		}
		if (in_array('created_at', $this->columns)) {
			$this->Cell(25, 10, 'Ingesado',1,0,'C');
		}
		if (in_array('updated_at', $this->columns)) {
				$this->Cell(25, 10, 'Actualizado',1,0,'C');
		}
		// foreach ($this->columns as $column) {
		// 	switch ($column) {
		// 		case 'bar_code':
		// case 'name':
		// case 'cost':
		// case 'price':
		// case 'stock':
		// case 'created_at':
		// case 'updated_at':
		// 	}
		// }
		$this->Ln();
	}

	function Footer() {
		$this->setMargins(10, 0);
		$this->SetY(-15);
		$this->setFont('Arial', 'I', 11);
		$this->Cell(40, 10, 'Pie de pagina, num: '.$this->PageNo().' de {nb}');
	}
}
