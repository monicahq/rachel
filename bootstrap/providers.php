<?php

declare(strict_types=1);

use EragLaravelDisposableEmail\LaravelDisposableEmailServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Providers\VoltServiceProvider::class,
    LaravelDisposableEmailServiceProvider::class,
];
