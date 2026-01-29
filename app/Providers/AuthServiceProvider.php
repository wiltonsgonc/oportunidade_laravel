<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('usuarios', function ($app, array $config) {
            return new \Illuminate\Auth\EloquentUserProvider($app['hash'], Usuario::class);
        });
    }
}