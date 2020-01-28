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

    public function password() {
        return view('auth.password');
    }

    public function update_password(Request $request) {
        $rules = [
            'mypassword' => 'required',
            'password' => 'required|confirmed',
        ];

        $messages = [
            'mypassword.required' => 'Este campo es requerido',
            'password.required' => 'Este campo es requerido',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('change-password')->withErrors($validator);
        } else {
            if (Hash::check($request->mypassword, Auth()->user()->password)) {
                $user = User::find(Auth()->user()->id);
                $user->update([
                    'password' => bcrypt($request->password),
                ]);
                return redirect()->route('change-password')
                                    ->with('status', 'Contraseña actualizada con exito');
            } else {
                return redirect()->route('change-password')
                                    ->with('message', 'La contraseña actual no coincide');

            }
        }
    }
}
