<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Paksa HTTPS di lingkungan non-lokal / reverse proxy cloud hosting (Render, Railway, Fly.io, Cloudflare, VPS)
        if (
            !app()->isLocal() ||
            config('app.env') === 'production' ||
            env('APP_ENV') === 'production' ||
            request()->header('x-forwarded-proto') === 'https' ||
            request()->isSecure() ||
            str_contains(request()->header('host', ''), 'railway.app') ||
            str_contains(request()->header('host', ''), 'onrender.com')
        ) {
            URL::forceScheme('https');
        }
    }
}
