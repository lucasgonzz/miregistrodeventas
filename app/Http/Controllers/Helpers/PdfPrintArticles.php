<?php

namespace App\Http\Controllers\Helpers;

use App\Article;
use App\Sale;
use App\Client;

require(__DIR__.'\..\fpdf\fpdf.php');
use fpdf;

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

