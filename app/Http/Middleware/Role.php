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
      
        $user = auth()->user();

    if (!$user || !$user->role) {
        abort(403);
    }

    $userRole = $user->role->role;

    // Convert comma-separated string to array
    $roles = explode(',', $role);

    // Trim extra spaces just in case
    $roles = array_map('trim', $roles);

    if (!in_array($userRole, $roles)) {
        switch ($userRole) {
            case 'Employee':
                return redirect()->route('employee.dashboard');
            case 'BDIC Super Admin':
            case 'Head of Service':
            case 'Commissioner':
            case 'Director':
                return redirect()->route('admin.dashboard');
            default:
                abort(403);
        }
    }
        
        // User has one of the required roles, proceed with the request
        return $next($request);
    }
}
