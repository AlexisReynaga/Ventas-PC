<?php

namespace App\Providers;

use App\Services\ExternalApi\ExternalApiClient;
use Illuminate\Support\ServiceProvider;

class ExternalApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ExternalApiClient::class, fn () => new ExternalApiClient());
        $this->app->alias(ExternalApiClient::class, 'external.api');
    }

    public function boot(): void
    {
        //
    }
}
