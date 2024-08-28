<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //PHPのロケールを日本語に設定
        // カレンダー画面上の曜日が英語表記ではなく日本語に表示。
        setlocale(LC_TIME, 'ja_JP.UTF-8');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
