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
    	return BarCode::where('user_id', Auth()->user()->id)
                        ->orderBy('id', 'DESC')
                        ->with('article')
                        ->get();
    }

    function generated() {
        return BarCode::where('user_id',  Auth()->user()->id)->get();
    }

    function store($bar_code, $amount, $size, $text) {
        $user = Auth()->user();
        $barCode = BarCode::where('user_id', $user->id)
                            ->where('name', $bar_code)
                            ->first();

        if ($barCode === null) {
        	BarCode::create([
        		'name' => $bar_code,
        		'amount' => $amount,
        		'user_id' => $user->id
        	]);
        }


        if ($text == 'true') {
            $text_below = true;
        } else {
            $text_below = false;
        }

        $pdf = new fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','',10);
        if ($size == 'lg') {
            $w = 60;
        } else if ($size == 'md') {
            $w = 37;
        } else if ($size == 'sm') {
            $w = 25;
        }
        $pdf->setY(5);
        $x = $pdf->GetX();
        $x_origin = $x;
        $y = $pdf->GetY();
        $user = Auth()->user();
        for ($i=0; $i < $amount ; $i++) {
            if(!is_dir(public_path()."/storage/barcodes/".$user->id)) {
                mkdir(public_path().'/storage/barcodes/'.$user->id);
            }
            barcode(public_path().'/storage/barcodes/'.$user->id.'/'.$bar_code.'.png', 
                    $bar_code, 20, 'horizontal', 'code128', $text_below);
            $pdf->Image(public_path().'/storage/barcodes/'.$user->id.'/'.$bar_code.'.png',$x,$y,$w,0,'PNG');
            $x += $w;
            if ($x > 210 - $w) {
                $x = $x_origin;
                if ($text_below) {
                    if ($size == 'lg') {
                        $y += 20;
                    } else if ($size == 'md') {
                        $y += 14;
                    } else if ($size == 'sm') {
                        $y += 9;
                    }
                } else {
                    if ($size == 'lg') {
                        $y += 12;
                    } else if ($size == 'md') {
                        $y += 8;
                    } else if ($size == 'sm') {
                        $y += 6;
                    }
                }
            }
        }

        $pdf->Output();
        exit;

    }

    function delete($id) {
        // return 'asd';
        BarCode::find($id)->delete();
    }
}
