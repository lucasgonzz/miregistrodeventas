<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// require('fpdf/fpdf.php');
use fpdf;
use App\Article;
use App\Sale;
use App\Http\Controllers\Helpers\PdfSaleClient;
use App\Http\Controllers\Helpers\PdfSaleCommerce;

class PdfController extends Controller
{

	function barCodeDirectory() {
        if(!is_dir(public_path()."/storage/barcodes/".Auth()->user()->id)) {
            mkdir(public_path().'/storage/barcodes/'.Auth()->user()->id);
        }
	}

	function printTicket($articles_id, $company_name) {
		require('barcode/barcode.php');
		require('fpdf/fpdf.php');
		$user = Auth()->user();
		$company_name = (bool)$company_name;
		$pdf = new fpdf();
		$pdf->addPage();
        $x = 10;
        $y = 10;
        $largo_ticket = 0;
        // $hubo_codigo_de_barras = false;
        $articles_id = explode('-', $articles_id);
        // $bar_code_printed = false;
        foreach ($articles_id as $article_id) {
        	$article = Article::find($article_id);
			$bar_code = $article->bar_code;
			$price = $article->price;
			$offer_price = $article->offer_price;
			$name = $article->name;

			// Se crea el directorio si no existe
			$this->barCodeDirectory();

	        $widths = [];
			// Se crea la imagen del codigo de barras si existe
	        if (!is_null($bar_code)) {
		        barcode(public_path().'/storage/barcodes/'.$user->id.'/'.$bar_code.'.png', 
		                $bar_code, 20, 'horizontal', 'code128', 1);
		        $width_image = getimagesize(public_path().'/storage/barcodes/'.$user->id.'/'.$bar_code.'.png');
		        $widths['bar_code'] = $width_image[0] * 50 / 200;
		        $bar_code_printed = true;
	        }

	        // Se obtienen los largos del nombre, del precio, del preci de oferta
	        // y del nombre de la compania
	        $widths['name'] = strlen($name)*2;
	        $widths['price'] = (strlen($this->price_ticket($price))+1)*5;
	        if (!is_null($offer_price)) {
	        	$offer_price_width = (strlen($this->price_ticket($offer_price))+1)*5;
	        	$old_price_width = (strlen($this->price_ticket($price))+1)*3;
	        	$widths['offer_price'] = $offer_price_width;
	        	$widths['old_price'] = $old_price_width;
	        	$widths['offer_price_old_price'] = $offer_price_width + $old_price_width;
	        }
	        $widths['company_name'] = strlen($user->company_name)*1.7;

	        // Se obtiene el valor mas largo
	        // entre el nombre, el precio y el codigo de barras
	        $largo_ticket = max(array(
	        						$widths['name'], 
	        						$widths['price'], 
	        						isset($widths['bar_code']) ? $widths['bar_code'] : null, 
	        						isset($widths['offer_price_old_price']) ? $widths['offer_price_old_price'] : null, 
	        						isset($widths['company_name']) ? $widths['company_name'] : null));

	        /* ----------------------------------------------------------------------------
				* Se le resta el largo del futuro ticket a lo que queda de pagina 
				* Si lo que va de x es mayor que queda de pagina se baja de linea
				* Si se pinto un codigo de barra se baja mas que si no se pinto
				* Si se pinto el nombre de la compania tambien
	        ----------------------------------------------------------------------------  */
	        if ($x > 205-$largo_ticket) {
	        	$x = 10;
	        	$y += 40;
	        }
        	if ($y + 40 > 280) {
        		// dd($name);
        		$y = 10;
        		$pdf->AddPage();
        	} 


	        /* ----------------------------------------------------------------------------
				* Se dibuja la linea de arriba
				* Se le suman cuatro porque tiene 2 de espacio entre los bordes
	        ----------------------------------------------------------------------------  */
        	$pdf->Line($x, $y, $x+$largo_ticket+4, $y);

	        /* ----------------------------------------------------------------------------
				* Se dibuja la linea de la izquierda
				* Si no tiene codigo de barras se baja menos
				* Si se pinta el nombre de la compania se baja mas
				* Si se pinto el nombre de la compania tambien
	        ----------------------------------------------------------------------------  */
	        $alto_linea = 22;
	        if (!is_null($bar_code)) {
	        	$alto_linea += 10;
	        }
	        if ($company_name) {
	        	$alto_linea += 4;
	        }
	        $pdf->Line($x, $y, $x, $y+$alto_linea);

	        // Se aumenta x para que tenga espacio desde los bordes
	        $x += 2;

			// Se escribe el nombre
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Arial', '', 12);
	        $pdf->SetXY($x, $y);
	        $pdf->Cell($largo_ticket, 7,$name,'B',0,'C');
			
	        /* ----------------------------------------------------------------------------
				* Se escribe el precio
				* Primero el signo de $ (peso)
	        ----------------------------------------------------------------------------  */
	        $pdf->SetXY($x, $y+9);
	        $pdf->Cell(4,4,'$',0,0,'L');

	        /* ----------------------------------------------------------------------------
				* Se tiene precio de oferta se escribe en rojo
				* Se le restan 4 al tamaño de la celda porque 4 es lo que 
				mide el signo de peso
				* Se dibuja el precio y despues se lo tacha
				* Se coloca X en el valor de x mas lo que mide el precio de oferta
				para que desde ahi arranque a dibujarce el precio actual
	        ----------------------------------------------------------------------------  */
			$pdf->SetFont('Arial', '', 28);
			if (!is_null($offer_price)) {
				$pdf->SetTextColor(226, 53, 53);
		        $pdf->Cell($largo_ticket-4,10,$this->price_ticket($offer_price),0,0,'L');

		        // Se dibuja el precio y despues se lo tacha
		        $pdf->SetTextColor(0,0,0);
		        $pdf->SetFont('Arial', '', 12);
		        $pdf->SetXY($x+$widths['offer_price'], $y+9);
		        $pdf->Cell(4,4,'$',0,0,'L');
		        $pdf->SetX($x+$widths['offer_price']+2);
		        $pdf->SetFont('Arial', '', 16);
		        $pdf->Cell($widths['old_price'], 5, $this->price_ticket($price),0,0);
		        $pdf->SetLineWidth(.6);
		        $pdf->Line($x+$widths['offer_price'], $y+10, $x+$widths['offer_price_old_price']+1, $y+12);
			} else {
				$pdf->SetTextColor(0,0,0);
		        $pdf->Cell($largo_ticket-4,10,$this->price_ticket($price),0,0,'L');
			}

	        // Si hay codigo de barra se escribe la imagen
	        if (!is_null($bar_code)) {
		        $pdf->Image(
		        				public_path().'/storage/barcodes/'.$user->id.'/'.$bar_code.'.png',
		        				$x,
		        				$y+20,
		        				$widths['bar_code'],
		        				0,
		        				'PNG'
		        			);
	        }

	        $pdf->SetLineWidth(.3);
	        if ($company_name) {
	        	$pdf->SetFont('Arial', 'I', 10);
	        	$pdf->SetTextColor(0,0,0);
	        	if (!is_null($bar_code)) {
	        		$pdf->SetXY($x, $y+30);
	        		$pdf->Cell($largo_ticket,5,$user->company_name,'T',0);
	        	} else {
	        		$pdf->SetXY($x, $y+20);
	        		$pdf->Cell($largo_ticket,5,$user->company_name,'T',0);
	        	}
	        }

	        // Linea derecha
		    $pdf->Line($x+$largo_ticket+2, $y, $x+$largo_ticket+2, $y+$alto_linea);
		    // Linea de abajo
	        $pdf->Line($x-2, $y+$alto_linea, $x+$largo_ticket+2, $y+$alto_linea);


	        // Se aumenta x con el campo mas largo y se le suman 4
	        // 2 del espacio que hay con la linea izquierda
	        // 2 del espacio que hay con la linea de la derecha
	        // 4 mas para que el proximo este separado
	        $x += $largo_ticket+6;

	        // Cada letra en 26 mide 5
        }
        $pdf->Output();
        exit;
	}

	function price_ticket($price) {
		return number_format($price, 2, ',', '.');
	}

	/* -------------------------------------------------------------------------------
		*
		* Pdf de las ventas
		*
 	-------------------------------------------------------------------------------*/

	function sale_client($company_name, $borders, $sale_id) {
        $pdf = new PdfSaleClient($sale_id, (bool)$company_name, (bool)$borders);
        $pdf->printSale();
	}

	function sale_commerce($company_name, $borders, $sale_id) {
        $pdf = new PdfSaleCommerce($sale_id, (bool)$company_name, (bool)$borders);
        $pdf->printSale();
        
  //       $articles = $sale->articles;
  //       $pdf->addPage();
  //       $pdf->setFont('Arial', '', 12);

  //       if ($per_page != 0) {
		// 	$count = 0;
		// 	$count_total = 0;
		// 	$cost = 0;
		// 	$price = 0;
		// 	foreach ($articles as $article) {
		// 		$count_total++;
		// 		if ($count < $per_page && $count_total < count($articles)) {
		// 			$cost += $article->cost;
		// 			$price += $article->price;
		// 			$count++;
		// 			$pdf->printArticle($pdf, $article, $borders, true);
		// 		} else {
		//         	$pdf->SetY(-40);
	 //        		$pdf->Cell(0,5,$count.' arículos en esta página',0,0,'R');
	 //        		$pdf->Ln();
	 //        		$pdf->Cell(0,5,'Suma de los costos de esta página: $'.$pdf->price($cost),0,0,'R');
	 //        		$pdf->Ln();
	 //        		$pdf->Cell(0,5,'Suma de los precios de esta página: $'.$pdf->price($price),0,0,'R');
	 //        		if (count($articles) > $count_total) {
	 //        			$pdf->addPage();
	 //        		}
		// 			$count = 0;
		// 			$cost = 0;
		// 			$price = 0;
		// 		}				
		// 	}
		// } else {
		// 	foreach ($articles as $article) {
		// 		$pdf->printArticle($pdf, $article, $borders, true);
		// 	}
		// }

  //       $pdf->Output();
  //       exit;
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
						$article->{$column} = $pdf->price($article->{$column});
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







