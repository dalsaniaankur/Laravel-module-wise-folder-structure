<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes\Models;

class CheckAdministrator
{
    public function handle($request, Closure $next)
    {
		if (!Auth::guard('administrator')->check()) {
           return redirect('administrator_login');
        }
		else
		{
			$user    = Auth::guard('administrator')->user();
			$user_id = $user->user_id;
			$allKey=\Config::get('user-configuration');
			Auth::shouldUse('administrator');
			foreach($allKey as $key=>$value)
			{
				$user_settings = \App\Classes\Models\Configuration::where('user_id',$user_id)
										   ->where('key',$key)->first();
										   
				if($user_settings!==null)
				{
					if($user_settings->value!=''){
					 \Config::set('user-configuration.'.$key.'.value',$user_settings->value);
					}
				}
				
			}
		}
		return $next($request);
    }
}
