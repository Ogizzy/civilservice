<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
      
        $user = $request->user();

        $userRole = $request->user()->role->role; 

        switch ($userRole) {
            case 'Employee':
                return redirect('employee.dashboard');
            case 'BDIC Super Admin':
            case 'Head of Service':
            case 'Commissioner':
            case 'Director':
                return redirect('dashboard');
            default:
                abort(403); // or redirect to login or error page
        }
        
        
        return $next($request);
    }
}
