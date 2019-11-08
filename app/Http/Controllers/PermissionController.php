<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Permission;

class PermissionController extends Controller
{
 	function index() {
 		$permissions = Permission::all();
 		return $permissions;
 	}   
}
