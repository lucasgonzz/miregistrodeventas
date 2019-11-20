<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
require('fpdf/fpdf.php');
use fpdf;
use App\Article;
use App\Sale;

class PdfController extends Controller
{

	/* -------------------------------------------------------------------------------
		*
		* Pdf de las ventas
		*
 	-------------------------------------------------------------------------------*/

	function ticket_client($company_name, $borders, $per_page, $sale_id) {
        $sale = Sale::find($sale_id);
        $client = $sale->client;
        $pdf = new PdfTicketClient($client, (bool)$company_name);
        if ((bool)$borders) {
        	$borders = 1;
        } else {
        	$borders = 'B';
        }
        $pdf->addPage();
        $pdf->setFont('Arial', '', 12);

        $articles = $sale->articles;

		if ($per_page != 0) {
			$count = 0;
			$count_total = 0;
			$cost = 0;
			$price = 0;
			foreach ($articles as $article) {
				$count_total++;
				if ($count < $per_page && $count_total < count($articles)) {
					$cost += $article->cost;
					$price += $article->price;
					$count++;
					$this->printArticle($pdf, $article, $borders);
				} else {
		        	$pdf->SetY(-40);
	        		$pdf->Cell(0,5,$count.' arículos en esta página',0,0,'R');
	        		// $pdf->Ln();
	        		// $pdf->Cell(0,5,'Suma de los costos de esta página: $'.$this->price($cost),0,0,'R');
	        		$pdf->Ln();
	        		$pdf->Cell(0,5,'Suma de los precios de esta página: $'.$this->price($price),0,0,'R');
	        		if (count($articles) > $count_total) {
	        			$pdf->addPage();
	        		}
					$count = 0;
					$cost = 0;
					$price = 0;
				}				
			}
		} else {
			foreach ($articles as $article) {
				$this->printArticle($pdf, $article, $borders);
			}
		}

        
        $pdf->Output();
        exit;
	}

	function ticket_commerce($company_name, $borders, $per_page, $sale_id) {
        $sale = Sale::find($sale_id);
        $client = $sale->client;
        $pdf = new PdfTicketCommerce($client, (bool)$company_name);
        if ((bool)$borders) {
        	$borders = 1;
        } else {
        	$borders = 'B';
        }
        $articles = $sale->articles;
        $pdf->addPage();
        $pdf->setFont('Arial', '', 12);

        if ($per_page != 0) {
			$count = 0;
			$count_total = 0;
			$cost = 0;
			$price = 0;
			foreach ($articles as $article) {
				$count_total++;
				if ($count < $per_page && $count_total < count($articles)) {
					$cost += $article->cost;
					$price += $article->price;
					$count++;
					$this->printArticle($pdf, $article, $borders, true);
				} else {
		        	$pdf->SetY(-40);
	        		$pdf->Cell(0,5,$count.' arículos en esta página',0,0,'R');
	        		$pdf->Ln();
	        		$pdf->Cell(0,5,'Suma de los costos de esta página: $'.$this->price($cost),0,0,'R');
	        		$pdf->Ln();
	        		$pdf->Cell(0,5,'Suma de los precios de esta página: $'.$this->price($price),0,0,'R');
	        		if (count($articles) > $count_total) {
	        			$pdf->addPage();
	        		}
					$count = 0;
					$cost = 0;
					$price = 0;
				}				
			}
		} else {
			foreach ($articles as $article) {
				$this->printArticle($pdf, $article, $borders, true);
			}
		}

        $pdf->Output();
        exit;
	}


	function printArticle($pdf, $article, $borders, $commerce = false) {
		if ($commerce) {
			$pdf->SetX(5);
		} else {
			$pdf->SetX(10);
		}
		$pdf->Cell(40,7,$article->bar_code,$borders,0);
    	$name = $article->name;
    	if (strlen($name) > 20) {
    		$name = substr($article->name, 0, 20) . ' ..';
    	}
    	$pdf->Cell(45,7,$name,$borders,0);
    	$pdf->Cell($commerce ? 25 : 27,7,'$'.$this->price($article->cost),$borders,0,'C');
    	$pdf->Cell($commerce ? 25 : 27,7,'$'.$this->price($article->price),$borders,0,'C');
    	$pdf->Cell($commerce ? 15 : 20,7,$article->pivot->amount,$borders,0,'C');
    	if ($commerce) {
	    	$pdf->Cell(25,7,'$'.$article->cost * $article->pivot->amount,$borders,0,'L');
    	}
    	$pdf->Cell($commerce ? 25 : 30,7,'$'.$article->price * $article->pivot->amount,$borders,0,'L');
    	$pdf->Ln();
	}

	/* -------------------------------------------------------------------------------
		*
		* Termina los pdf de las ventas
		*
 	-------------------------------------------------------------------------------*/




	/* -------------------------------------------------------------------------------
		*
		* Imprimir los articulos del listado
		*
 	-------------------------------------------------------------------------------*/

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
		$pos = strpos($price, '.');
		if ($pos != false) {
			$centavos = explode('.', $price)[1];
			$new_price = explode('.', $price)[0];
			if ($centavos != '00') {
				$new_price += ".$centavos";
				return number_format($new_price, 2, ',', '.');
			} else {
				return number_format($new_price, 0, '', '.');			
			}
		} else {
			return number_format($price, 0, '', '.');
		}
	}
}

class PdfTicketClient extends fpdf {

	function __construct($client, $company_name) {
		parent::__construct();
		$this->client = $client;
		$this->company_name = $company_name;
	}
	
	function Header() {
		$this->SetFont('Arial', '', 11, 'C');
		$this->Write(5,date('d/m/y'));
		$this->SetX(-60);
		$this->Write(5,'Cliente: '.$this->client->name);
		$this->SetY(20);
		if ($this->company_name) {
			$this->SetFont('Arial', 'B', 16, 'C');
			$this->Cell(0,5,Auth()->user()->company_name,0,0,'C');
			$this->SetY(30);
		}
		$this->SetX(10);
		$this->SetFont('Arial', 'B', 14, 'C');
		$this->Cell(40, 5, 'Codigo', 0, 0, 'C');
		$this->Cell(45, 5, 'Artículo', 0, 0, 'C');
		$this->Cell(27, 5, 'Costo', 0, 0, 'C');
		$this->Cell(27, 5, 'Precio', 0, 0, 'C');
		$this->Cell(20, 5, 'Cant.', 0, 0, 'C');
		$this->Cell(30, 5, 'Sub Total', 0, 0, 'C');
		$this->SetLineWidth(1);
		$this->SetDrawColor(100, 174, 238);
		if ($this->company_name) {
			$this->SetY(39);
			$this->Line(10, 37, 200, 37);
		} else {
			$this->Line(10, 27, 200, 27);
			$this->SetY(29);
		}
	}

	function Footer() {
		$this->SetFont('Arial', '', 11);
		$this->AliasNbPages();
		$this->SetY(-20);
		$this->Write(5,'Hoja '.$this->PageNo().'/{nb}');
	}
}

class PdfTicketCommerce extends fpdf {

	function __construct($client, $company_name) {
		parent::__construct();
		$this->client = $client;
		$this->company_name = $company_name;
	}
	
	function Header() {
		$this->SetFont('Arial', '', 11, 'C');
		$this->Write(5,date('d/m/y'));
		$this->SetX(-60);
		$this->Write(5,'Cliente: '.$this->client->name);
		$this->SetY(20);
		if ($this->company_name) {
			$this->SetFont('Arial', 'B', 16, 'C');
			$this->Cell(0,5,Auth()->user()->company_name,0,0,'C');
			$this->SetY(30);
		}
		$this->SetX(5);
		$this->SetFont('Arial', 'B', 14, 'C');
		$this->Cell(40, 5, 'Codigo', 0, 0, 'C');
		$this->Cell(45, 5, 'Artículo', 0, 0, 'C');
		$this->Cell(25, 5, 'Costo', 0, 0, 'C');
		$this->Cell(25, 5, 'Precio', 0, 0, 'C');
		$this->Cell(15, 5, 'Cant.', 0, 0, 'C');
		$this->Cell(25, 5, 'T. Costo', 0, 0, 'C');
		$this->Cell(25, 5, 'Sub Total', 0, 0, 'C');
		$this->SetLineWidth(1);
		$this->SetDrawColor(100, 174, 238);
		if ($this->company_name) {
			$this->SetY(39);
			$this->Line(5, 37, 205, 37);
		} else {
			$this->Line(5, 27, 205, 27);
			$this->SetY(29);
		}
	}

	function Footer() {
		$this->SetFont('Arial', '', 11);
		$this->AliasNbPages();
		$this->SetY(-20);
		$this->Write(5,'Hoja '.$this->PageNo().'/{nb}');
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
