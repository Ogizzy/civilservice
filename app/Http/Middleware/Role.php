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
        
        // Check if user is authenticated
        if (!$user) {
            return redirect('login');
        }

        $userRole = $user->role->role;
        
        // Check if user has any of the required roles
        if (!in_array($userRole, $role)) {
            // User doesn't have required role, redirect based on their actual role
            switch ($userRole) {
                case 'Employee':
                    return redirect()->route('employee.dashboard');
                case 'BDIC Super Admin':
                case 'Head of Service':
                case 'Commissioner':
                case 'Director':
                    return redirect()->route('dashboard');
                default:
                    abort(403);
            }
        }
        
        // User has one of the required roles, proceed with the request
        return $next($request);
    }
}
