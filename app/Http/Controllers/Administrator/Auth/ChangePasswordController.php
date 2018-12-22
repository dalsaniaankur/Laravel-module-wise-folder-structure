<?php

namespace App\Http\Controllers\Administrator\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Hash;
use Validator;

class ChangePasswordController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('administrator');
    }
    /**
     * Where to redirect users after password is changed.
     *
     * @var string $redirectTo
     */
    protected $redirectTo = '/administrator_change_password';

    /**
     * Change password form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        $user = Auth::guard('administrator')->getUser();

        return view('administrator.auth.change_password', compact('user'));
    }

    /**
     * Change password.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $user = Auth::guard('administrator')->getUser();
        $this->validator($request->all())->validate();
        if (Hash::check($request->get('current_password'), $user->password)) {
            $user->password = $request->get('new_password');
            $user->save();
            return redirect($this->redirectTo)->with('success', 'Password change successfully!');
        } else {
            return redirect()->back()->withErrors('Current password is incorrect');
        }
    }

    /**
     * Get a validator for an incoming change password request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
		$min=\Config::get('administrator-configuration.default_password_length');
		return Validator::make($data, [
            'current_password' => 'required',
            'new_password' => 'required|min:'.$min.'|confirmed',
        ]);
    }
}
