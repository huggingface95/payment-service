<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Laravel\Passport\PassportServiceProvider as LaravelPassportServiceProvider;

class PassportServiceProvider extends LaravelPassportServiceProvider
{
    protected function registerGuard()
    {
        Auth::resolved(function ($auth) {
            Auth::extend('passport', function ($app, $name, array $config) {
                return tap($this->makeGuard($config), function ($guard) {
                    $this->app->refresh('request', $guard, 'setRequest');
                });
            });
        });
    }
}
