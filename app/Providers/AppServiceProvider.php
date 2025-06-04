<?php

namespace App\Providers;

use App\Models\UserPermission;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('usercan', function ($feature, $ability = 'can_edit') {
            $user = auth()->user();
    
            if (!$user) return false;
    
            $permission = UserPermission::where('role_id', $user->role_id)
                ->whereHas('feature', fn ($query) => $query->where('feature', $feature))
                ->first();
    
            return $permission && $permission->{$ability};
            
        });

        Blade::if('userActive', function () {
            return auth()->check() && auth()->user()->isActive();
        });
    
        Blade::if('userSuspended', function () {
            return auth()->check() && auth()->user()->isSuspended();
        });
    
        Blade::if('userBanned', function () {
            return auth()->check() && auth()->user()->isBanned();
        });
        
        Paginator::useBootstrap();
    }

    
    
}
