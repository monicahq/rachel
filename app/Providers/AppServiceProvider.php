<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

final class AppServiceProvider extends ServiceProvider
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
        // Enable strict mode for Eloquent models in non-production environments
        Model::shouldBeStrict(! app()->isProduction());

        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));

        Passport::tokensCan([
            'read' => 'Read data',
            'write' => 'Write data',
        ]);

        Passport::defaultScopes([
            'read',
        ]);

        Passport::authorizationView(
            view: 'auth.authorization',
        );
    }
}
