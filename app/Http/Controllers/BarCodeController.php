<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
require('fpdf/fpdf.php');
require('barcode/barcode.php');
use FPDF;
use App\BarCode;

class BarCodeController extends Controller
{
    function index() {
    	return BarCode::where('user_id', Auth()->user()->id)->get();
    }

    function store($bar_code, $amount, $size, $text) {

    	BarCode::create([
    		'name' => $bar_code,
    		'amount' => $amount,
    		'user_id' => Auth()->user()->id
    	]);

        if ($text == 'true') {
            $text_below = true;
        } else {
            $text_below = false;
        }

        $pdf = new fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','',10);
        if ($size == 'lg') {
            if (strlen($bar_code)>8) {
                $w = 70;
            } else {
                $w = 50;
            }
        } else if ($size == 'md') {
            if (strlen($bar_code)>8) {
                $w = 50;
            } else {
                $w = 30;
            }
        } else if ($size == 'sm') {
            if (strlen($bar_code)>8) {
                $w = 35;
            } else {
                $w = 20;
            }
        }

        $pdf->setX(0);
        $pdf->setY(5);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $user = Auth()->user();
        for ($i=0; $i < $amount ; $i++) {
            if(!is_dir(public_path()."/storage/barcodes/".$user->id)) {
                mkdir(public_path().'/storage/barcodes/'.$user->id);
            }
            barcode(public_path().'/storage/barcodes/'.$user->id.'/'.$bar_code.'.png', 
                    $bar_code, 20, 'horizontal', 'code128', $text_below);
            $pdf->Image(public_path().'/storage/barcodes/'.$user->id.'/'.$bar_code.'.png',$x,$y,$w,0,'PNG');
            $x = $x+$w;
            if ($x > 200) {
                $x = 10;
                if ($text_below) {
                    $y += 20;
                } else {
                    $y += 15;
                }
            }
        }


        $pdf->Output();
        exit;

    }

    function generate($bar_code) {
        BarCode::create([
            'name' => $request->barCode['name'],
            'amount' => (int)$request->barCode['amount'],
            'user_id' => Auth()->user()->id
        ]);
        $pdf=new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','',10);

        $pdf->Code128(50,20,$bar_code,80,20);
        $pdf->SetXY(50,45);

        $pdf->Output();
    }
}
