<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes\Models;

class CheckMember
{
    public function handle($request, Closure $next)
    {
		if (!Auth::guard('member')->check()) {
           return redirect('member_login');
        }
		return $next($request);
    }
}
