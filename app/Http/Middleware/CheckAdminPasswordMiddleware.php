<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Hash;
use Session;

class CheckAdminPasswordMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->admin === true && Hash::check('sitarium', Auth::user()->getAuthPassword())) {
            Session::put('alertAdminWithDefaultPassword', true);
        } else {
            Session::forget('alertAdminWithDefaultPassword');
        }

        return $next($request);
    }
}
