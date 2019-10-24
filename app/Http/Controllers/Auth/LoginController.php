<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
    
    public function login() {
        $credentials = $this->validate(request(), [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

        if(Auth::attempt($credentials)){
            return redirect('/');
        }
        return redirect('login')->with('errorLogin', 'Datos incorrectos, verifique sus datos por favor');
    }
    public function username() {
        return 'name';
    }
}
