<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
require('fpdf/fpdf.php');
use fpdf;
use App\Article;

class PdfController extends Controller
{
    function articles($columns_string, $articles_ids_string, $orientation, $header = null) {

    	$articles;
    	if ($articles_ids_string == 'todos') {
    		$articles = Article::all();
    	} else {
    		$articles_ids = explode('-', $articles_ids_string);
	    	foreach ($articles_ids as $id_article) {
	    		$articles[] = Article::find($id_article);
	    	}
    	}
    	$columns_ = explode('-', $columns_string);
    	$columns = [];

    	foreach ($columns_ as $column) {
    		switch ($column) {
    			case 'bar_code':
    				$columns['bar_code'] = 35;
    				break;
    			case 'name':
    				$columns['name'] = 50;
    				break;
    			case 'cost':
    				$columns['cost'] = 20;
    				break;
    			case 'price':
    				$columns['price'] = 20;
    				break;
    			case 'previus_price':
    				$columns['previus_price'] = 20;
    				break;
    			case 'stock':
    				$columns['stock'] = 15;
    				break;
    			case 'created_at':
    				$columns['created_at'] = 25;
    				break;
    			case 'updated_at':
    				$columns['updated_at'] = 25;
    				break;
    		}
    	}

    	if (!is_null($header)) {
    		$header = explode('-', $header);
    	}
		$pdf = new Pdf($orientation, $columns, $header);
		$pdf->AliasNbPages();
		$pdf->addPage();
		$pdf->setFont('Times', '', 14);
		foreach ($articles as $article) {
			foreach ($columns as $column => $w) {
				if ($column=='created_at' || $column=='updated_at') {
					$pdf->Cell($w,7,date_format($article->{$column}, 'd/m/y'),'B',0,'C');
				} else {
					$align = 'L';
					if ($column=='name') {
						if (strlen($article->{$column}) > 19) {
							$article->{$column} = substr($article->{$column}, 0, 19) . '..';
						}
					}
					if ($column=='price' || $column=='cost' || $column=='previus_price') {
						$align = 'R';
						$article->{$column} = $this->price($article->{$column});
					}
					if ($column=='stock') {
						$align = 'R';
					}
					$pdf->Cell($w,7,$article->{$column},'B',0,$align);
				}
			}
			$pdf->Ln();
		}

		$pdf->Output();
		exit;
    }

	function price($price) {
		$centavos = explode('.', $price)[1];
		$new_price = explode('.', $price)[0];
		if ($centavos != '00') {
			$new_price += ".$centavos";
			return number_format($new_price, 2, ',', '.');
		} else {
			return number_format($new_price, 0, '', '.');			
		}
	}
}


class Pdf extends fpdf {

	function __construct($orientation, $columns, $header) {
		if ($orientation == 'normal') {
			parent::__construct('P','mm','A4');
		} else {
			parent::__construct('L','mm','A4');
		}
		$this->orientation = $orientation;
		$this->columns = $columns;
		$this->header = $header;
	}

	function Header() {

		// Margenes
		$width = 0;
		foreach ($this->columns as $column => $w) {
			$width += $w;
		}
		if ($this->orientation == 'normal') {
			$margins = (210 - $width) / 2;
		} else {
			$margins = (297 - $width) / 2;
		}
		$this->SetMargins($margins, 10, $margins);

		$this->SetFont('Arial','',11);
		// Marca de la empresa
		$this->SetX(10);
		$this->Write(5,'Miregistrodeventas.com');

		$this->SetY(15);
		$this->SetX(10);

		if (!is_null($this->header)) {
			// Fecha
			if (in_array('date', $this->header)) {
				$this->Write(5,date('d/m/y'));
			}

			// Nombre del negocio del usuario
			if (in_array('company_name', $this->header)) {
				$this->SetY(20);
				$this->SetFont('Arial','B',16);
				$this->Cell(0,5,Auth()->user()->company_name,0,0,'C');
			}
		}
		$this->SetY(30);
		$this->setFont('Arial', 'B', 12);

		foreach ($this->columns as $column => $w) {
			switch ($column) {
				case 'bar_code':
					$column_es = 'Código';
					break;
				case 'name':
					$column_es = 'Nombre';
					break;
				case 'cost':
					$column_es = 'Costo';
					break;
				case 'price':
					$column_es = 'Precio';
					break;
				case 'stock':
					$column_es = 'Stock';
					break;
				case 'previus_price':
					$column_es = 'P. Anterior';
					break;
				case 'created_at':
					$column_es = 'Ingresado';
					break;
				case 'updated_at':
					$column_es = 'Actualizado';
					break;
			}
			$this->Cell($w,5,$column_es,0,0,'C');
			$this->Line($margins, 37, $margins+$width, 37);
		}
		$this->Ln(10);
	}

	function Footer() {
		$this->AliasNbPages();
		$this->SetY(-15);
		$this->SetX(10);
		$this->setFont('Arial', '', 11);
		$this->Write(5,$this->PageNo().'/{nb}');
	}
}
