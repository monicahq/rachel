<?php

declare(strict_types=1);

namespace App\Providers;

use App\Helpers\CollectionHelper;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        if (! Collection::hasMacro('sortByCollator')) {
            Collection::macro('sortByCollator', function (callable|string $callback): Collection {
                /** @var Collection */
                $collect = $this;

                return CollectionHelper::sortByCollator($collect, $callback);
            });
        }
    }
}
