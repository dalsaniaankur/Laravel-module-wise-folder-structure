<?php

namespace App\Http\Controllers\Administrator\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
//Password Broker Facade
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Auth;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/administrator/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
	
	public function showResetForm(Request $request, $token = null)
    {
        return view('administrator.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
	
	//returns Password broker of seller
    public function broker()
    {
        return Password::broker('administrator');
    }

    //returns authentication guard of seller
    protected function guard()
    {
        return Auth::guard('administrator');
    }
}
