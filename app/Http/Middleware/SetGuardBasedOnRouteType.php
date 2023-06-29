<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class SetGuardBasedOnRouteType
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
        if ($request->is('api/login')) {
            Auth::shouldUse('web');
        } else {
            Auth::shouldUse('api');
        }
    
        return $next($request);
    }
}
