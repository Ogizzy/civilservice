<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHod
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $employee = auth()->user()?->employee;

    if (!$employee || !$employee->isHod()) {
        abort(403, 'Access denied. HOD only.');
    }

        return $next($request);
    }
}
