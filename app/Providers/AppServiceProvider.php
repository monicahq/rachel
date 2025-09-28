<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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

        // Enable lazy loading prevention in non-production environments
        // This will throw an exception if a lazy loading query is attempted
        // ex: $user->posts without having called with('posts') in the query
        Model::preventLazyLoading(! app()->isProduction());

        // Throws an exception if you try to set an attribute that doesn’t exist on the model’s $fillable or actual DB columns.
        Model::preventSilentlyDiscardingAttributes();

        // Throws an exception if you try to read an attribute that isn’t actually on the model
        Model::preventAccessingMissingAttributes();
    }
}
