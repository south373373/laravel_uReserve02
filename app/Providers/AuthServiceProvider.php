<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// 追加分
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //追記分
        // 管理責任者
        Gate::define('admin',function($user){
            return $user->role === 1;
        });
        // 管理者
        Gate::define('manager-higher',function($user){
            return $user->role > 0 && $user->role <= 5;
        });
        // ユーザー会員
        Gate::define('user-higher',function($user){
            return $user->role > 0 && $user->role <= 9;
        });
    }
}
