<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        logout as performLogout;
    }

    public function logout(Request $request)
    {
        $this->performLogout($request);
        return redirect('/');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function showLoginForm() {
        return view('auth.login');
    }
    
    public function login_owner() {
        $credentials = $this->validate(request(), [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

        if(Auth::attempt($credentials)){
            return redirect('/');
        }
        return redirect()->route('show-login-form')->with('errorLogin', 'Datos incorrectos, verifiquelos por favor');

    }

    public function login_employee() {
        $credentials = $this->validate(request(), [
            'commerce_name' => 'required|string',
            'employee_name' => 'required|string',
            'employee_password' => 'required|string',
        ]);
        if (Auth::attempt(['name' => request('employee_name'), 'password' => request('employee_password')])){
            if(Auth()->user()->owner->company_name == request('commerce_name')){
                // $owner = User::where('')
                return redirect('/');
            }
            return redirect('login')->with('errorLogin', 'Anduvo mal');
        }

        return redirect('login')->with('errorLogin', 'Datos incorrectos, verifique sus datos por favor');
    }

    public function username() {
        return 'name';
    }
}
