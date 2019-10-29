<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
	function __construct() {
        $this->middleware('auth');
	}

	function index() {
		$user = Auth()->user();
		if ($user->hasRole('provider')) {
    		return redirect()->route('vender.provider');
    		// return view('provider.main.vender');
			$this->vender_provider();
		} else if ($user->hasRole('commerce')) {
    		return redirect()->route('vender.commerce');
    		// return view('commerce.main.vender');
			$this->vender_commerce();
		}
	}

    // Providers

    function provider_vender() {
    	return view('provider.main.vender');
    }
    function provider_ingresar() {
        return view('provider.main.ingresar');
    }
    function provider_listado() {
        return view('provider.main.listado');
    }
    function provider_ventas() {
        return view('provider.main.ventas');
    }

    // Commerce
    function commerce_vender() {
    	return view('commerce.main.vender');
    }
    function commerce_ingresar() {
        return view('commerce.main.ingresar');
    }
    function commerce_listado() {
        return view('commerce.main.listado');
    }
    function commerce_ventas() {
        return view('commerce.main.ventas');
    }
}