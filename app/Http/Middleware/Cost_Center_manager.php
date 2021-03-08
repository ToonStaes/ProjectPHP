<?php

namespace App\Http\Middleware;

use Closure;

class Cost_Center_manager
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
        if (auth()->user()->isCost_Center_manager) {
            return $next($request);
        }
        return abort(403, 'Alleen kostenplaatsverantwoordelijken kunnen deze pagina bekijken');
    }
}
