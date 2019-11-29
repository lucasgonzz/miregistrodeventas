<?php

namespace App\Http\Controllers\Helpers;

use App\Article;
use App\Sale;
require(__DIR__.'\..\fpdf\fpdf.php');
use fpdf;

class PdfPrintArticle extends fpdf {
	
	function printArticle($pdf, $article, $borders, $articles_cost) {
        dd('hola');
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
        if ($articles_cost) {
        	$pdf->Cell($commerce ? 25 : 27,7,'$'.$this->price($article->cost),$borders,0,'C');
        }
        $pdf->Cell($commerce ? 25 : 27,7,'$'.$this->price($article->price),$borders,0,'C');
        $pdf->Cell($commerce ? 15 : 20,7,$article->pivot->amount,$borders,0,'C');
        if ($commerce) {
            $pdf->Cell(25,7,'$'.$article->cost * $article->pivot->amount,$borders,0,'L');
        }
        $pdf->Cell($commerce ? 25 : 30,7,'$'.$article->price * $article->pivot->amount,$borders,0,'L');
        $pdf->Ln();
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