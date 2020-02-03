<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
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
    		'company_name' => $user->company_name,
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

    function getUser() {
        return Auth()->user();
    }

    function getCompanyName() {
        return Auth()->user()->company_name;
    }

    function setCompanyName($company_name) {
        $user_id = Auth()->user()->id;
        $user = User::find($user_id);
        $user->company_name = $company_name;
        $user->save();
    }

    public function password() {
        return view('auth.password');
    }

    public function update_password(Request $request) {

        if (Hash::check($request->password, Auth()->user()->password)) {
            $user = User::find(Auth()->user()->id);
            $user->update([
                'password' => bcrypt($request->new_password),
            ]);
            return response('ok');
        } else {
            return response('no');
        }
    }
}
