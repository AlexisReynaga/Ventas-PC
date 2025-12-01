<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- AGREGA ESTA LÍNEA

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Agrega este bloque IF:
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}