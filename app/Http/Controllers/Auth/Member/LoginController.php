<?php

namespace App\Http\Controllers\Auth\Member;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Classes\Models\Members\Members;
use Auth;

class LoginController extends Controller{

    use AuthenticatesUsers;

	protected $redirectTo = '/member/home';

    public function __construct()
    {
 		$this->middleware('guest_member');
    }

    public function showLoginForm(){
        return view('auth.member.login');
    }
	
	protected function guard()
    {
        return Auth::guard('member');
    }
}
