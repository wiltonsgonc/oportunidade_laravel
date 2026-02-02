<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stevenmaguire\OAuth2\Client\Provider\Keycloak as KeycloakProvider;

class KeycloakServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(KeycloakProvider::class, function ($app) {
            return new KeycloakProvider([
                'authServerUrl'         => config('keycloak.base_url'),
                'realm'                 => config('keycloak.realm'),
                'clientId'              => config('keycloak.client_id'),
                'clientSecret'          => config('keycloak.client_secret'),
                'redirectUri'           => config('keycloak.redirect_uri'),
                'version'               => '21.0.1',
            ]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}