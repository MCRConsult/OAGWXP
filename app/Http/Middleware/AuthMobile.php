<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class AuthMobile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (! hrOperatingUnit() ) { // hrOperatingUnit -> helper function app/Helpers/helps.php
                return redirect()->route('operating-unit.index', ['device' => 'mobile']);
            }

            return $next($request);
        }
        if ($request->ajax()) {
            return response('Unauthorized.', 401);
        } else {
            return redirect()->guest('mobile/login');
        }
    }
}
