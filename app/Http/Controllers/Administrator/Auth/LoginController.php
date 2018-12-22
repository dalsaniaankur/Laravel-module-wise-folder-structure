<?php

namespace App\Http\Controllers\Administrator\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Administrator\Administrator;

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
    //protected $redirectTo = '/home';
	protected $redirectTo = '/administrator/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
 		$this->middleware('guest')->except('logout');
    }
	/**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('administrator.auth.login');
    }
	
	protected function guard()
    {
        return Auth::guard('administrator');
    }
	
	public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
	    return redirect('/administrator_login');
    }
}
