<?php

namespace App\Http\Controllers\Auth\Member;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

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
    protected $redirectTo = '/home';
   
    public function __construct(){

 	   $this->middleware('member');
    }
	
	public function logout(Request $request)
    {
     	if(Auth::guard('member')->check()){

			Auth::guard('member')->logout();
			$request->session()->invalidate();
		}
		return redirect('/home');
    }
}
