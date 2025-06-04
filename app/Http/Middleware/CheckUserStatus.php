<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next, ...$allowedStatuses)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // If no specific statuses provided, only allow active users
        if (empty($allowedStatuses)) {
            $allowedStatuses = ['active'];
        }

        if (!in_array($user->status, $allowedStatuses)) {
            // Log the user out if they're banned
            if ($user->status === 'banned') {
                Auth::logout();
                $notification = array(
                    'message' => 'Your account has been banned. Please contact administrator.',
                    'alert-type' => 'error'
                );
                return redirect()->route('login')->with($notification);
            }

            // For suspended users, show restriction message
            if ($user->status === 'suspended') {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Your account is suspended. Access denied.'], 403);
                }
                
                $notification = array(
                    'message' => 'Your account is suspended. You cannot perform this action.',
                    'alert-type' => 'warning'
                );
                return redirect()->route('login')->with($notification);
            }
        }

        return $next($request);
    }
}