<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

	function getEmployees() {
    	$user = Auth()->user();
		return User::where('belongs_to', $user->id)->get();
	}

	function deleteEmployee($id) {
		User::find($id)->delete();
	}

    function saveEmployee(Request $request) {
    	$user = Auth()->user();
    	$employee = User::create([
    		'name' => ucwords($request->employee['name']),
    		'password' => Hash::make($request->employee['password']),
    		'belongs_to' => $user->id,
    	]);

    	if ($user->hasRole('provider')) {
            $employee->syncRoles('provider');
    	} else {
            $employee->syncRoles('commerce');
    	}
    	$employee->permissions()->attach($request->employee['permissions']);
    }
}
