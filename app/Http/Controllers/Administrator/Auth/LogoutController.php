<?php

namespace App\Http\Controllers\Administrator\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Administrator\Administrator;

class LogoutController extends Controller
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
   
    public function __construct()
    {
 	  //  $this->middleware('administrator_login_check')->except('administrator_logout');
    }
	
	public function logout(Request $request)
    {
     	if(Auth::guard('administrator')->check())
		{
			Auth::guard('administrator')->logout();
			$request->session()->invalidate();
		}
		return redirect('/administrator/home');
    }
}
