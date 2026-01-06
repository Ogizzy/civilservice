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
    $roles = array_map('trim', explode(',', $role));

    if (!in_array($userRole, $roles)) {
        // Prevent redirect loops by checking current route name
        $currentRoute = $request->route()->getName();

        switch ($userRole) {
            case 'Employee':

                if ($currentRoute !== 'employee.dashboard') {
                    return redirect()->route('employee.dashboard');
                }
                break;

            case 'BDIC Super Admin':
            case 'Head of Service':
            case 'Commissioner':
            case 'Director':
            case 'MDA Head':
                
                if ($currentRoute !== 'admin.dashboard') {
                    return redirect()->route('admin.dashboard');
                }
                break;

            default:
                abort(403);
        }
    }
   // User has one of the required roles, proceed with the request
        return $next($request);
    }
}
