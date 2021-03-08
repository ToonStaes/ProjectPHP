<?php

namespace App\Http\Middleware;

use Closure;

class Financial_Employee
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
        if (auth()->user()->isFinancial_employee) {
            return $next($request);
        }
        return abort(403, 'Alleen financiële werknemers kunnen deze pagina bekijken');
    }
}
