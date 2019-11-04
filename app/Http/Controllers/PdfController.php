<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
require('fpdf/fpdf.php');
use fpdf;
use App\Article;

class PdfController extends Controller
{
    function index() {

    	$articles = Article::all();

		$pdf = new Pdf();
		$pdf->AliasNbPages();
		$pdf->addPage();
		$pdf->setFont('Times', '', 14);
		$pdf->setMargins(10, 0);
		foreach ($articles as $article) {
			$name = $article->name;
			if (strlen($name) > 19) {
				$name = substr($name, 0, 19) . '..';
			}
			// $pdf->SetY(10);
			$pdf->Cell(35,10,123456789123, 1);
			$pdf->Cell(50,10,$name, 1);
			$pdf->Cell(20,10,$article->cost,1);
			$pdf->Cell(20,10,$article->price,1);
			$pdf->Cell(15,10,$article->stock,1);
			$pdf->Cell(25,10,date_format($article->created_at, 'd/m/y'),1);
			$pdf->Cell(25,10,date_format($article->updated_at, 'd/m/y'),1);
			$pdf->Ln();
		}

		$pdf->Output();
		exit;
    }
}


class Pdf extends fpdf {

	function Header() {
		$this->setFont('Arial', 'B', 12);
		$this->setY(10);
		// $this->setLineWidth(1);
		// $this->SetFillColor(200,220,255);
		// $this->Cell(80);
		$this->Cell(35, 10, 'Codigo',1,0,'C');
		$this->Cell(50, 10, 'Nombre',1,0,'C');
		$this->Cell(20, 10, 'Costo',1,0,'C');
		$this->Cell(20, 10, 'Precio',1,0,'C');
		$this->Cell(15, 10, 'Stock',1,0,'C');
		$this->Cell(25, 10, 'Ingesado',1,0,'C');
		$this->Cell(25, 10, 'Actualizado',1,0,'C');
		$this->Ln();
	}

	function Footer() {
		$this->setMargins(10, 0);
		$this->SetY(-15);
		$this->setFont('Arial', 'I', 11);
		$this->Cell(40, 10, 'Pie de pagina, num: '.$this->PageNo().' de {nb}');
	}
}
