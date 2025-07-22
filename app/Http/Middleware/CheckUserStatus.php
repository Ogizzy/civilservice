<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CheckUserStatus
{
     public function handle(Request $request, Closure $next, ...$allowedStatuses)
    {
        $currentRoute = Route::currentRouteName();
        $currentUri = $request->path();
        
        $excludedRoutes = [
            'login',
            'logout',
            'password.request',
            'password.email',
            'password.reset',
            'password.update',
            'password.confirm',
            'verification.notice',
            'verification.verify',
            'verification.send',
            'register',
        ];

        // Check if current route should be excluded
        if ($currentRoute && in_array($currentRoute, $excludedRoutes)) {
            return $next($request);
        }
        
        // Check if current URI should be excluded (covers unnamed routes)
        $excludedUris = [
            'login',
            'register',
            'password/reset',
            'password/email',
            'password/confirm',
            'email/verify',
            'forgot-password',
            'reset-password',
        ];
        
        foreach ($excludedUris as $excludedUri) {
            if ($request->is($excludedUri) || $request->is($excludedUri . '/*')) {
                return $next($request);
            }
        }

        $user = Auth::user();

        // If not logged in, redirect to login
        if (!$user) {
            return redirect()->route('login');
        }

        // Set default allowed statuses
        if (empty($allowedStatuses)) {
            $allowedStatuses = ['active'];
        }

        // Check if user status is allowed
        if (!in_array($user->status, $allowedStatuses)) {
            // Store status before logout
            $userStatus = $user->status;
            
            // Logout user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect with message
            return redirect()->route('login')->with([
                'message' => 'Your account is ' . $userStatus . '. Please contact Administrator.',
                'alert-type' => 'error'
            ]);
        }

        return $next($request);
}
}