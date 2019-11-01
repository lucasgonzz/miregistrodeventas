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
		$pdf->setMargins(5, 0);
		$pdf->setFont('Times', '', 14);
		foreach ($articles as $article) {
			$pdf->Cell(25,20,$article->bar_code,1);
			$pdf->Cell(25,20,$article->name,1);
			$pdf->Cell(25,20,$article->cost,1);
			$pdf->Cell(25,20,$article->price,1);
			$pdf->Cell(25,20,$article->stock,1);
			$pdf->Cell(25,20,date_format($article->created_at, 'd/m/y'),1);
			$pdf->Cell(25,20,date_format($article->updated_at, 'd/m/y'),1);
			$pdf->Ln();
		}
		// for($i=0; $i<50; $i++) {
		// 	$pdf->Cell(40,20, "Holaaaa $i", 1);
		// 	$pdf->Ln();	
		// }
		$pdf->Output();
		exit;
    }
}


class Pdf extends fpdf {

	function Header() {
		$this->setFont('Arial', 'B', 12);
		$this->setMargins(5, 0);
		// $this->setLineWidth(1);
		// $this->SetFillColor(200,220,255);
		// $this->Cell(80);
		$this->Cell(25, 10, 'Codigo',1,0,'C');
		$this->Cell(25, 10, 'Nombre',1,0,'C');
		$this->Cell(25, 10, 'Costo',1,0,'C');
		$this->Cell(25, 10, 'Precio',1,0,'C');
		$this->Cell(25, 10, 'Stock',1,0,'C');
		$this->Cell(25, 10, 'Ingesado',1,0,'C');
		$this->Cell(25, 10, 'Actualizado',1,0,'C');
		$this->Ln();
	}

	function Footer() {
		$this->setMargins(5, 0);
		$this->SetY(-15);
		$this->setFont('Arial', 'I', 11);
		$this->Cell(40, 10, 'Pie de pagina, num: '.$this->PageNo().' de {nb}');
	}
}
