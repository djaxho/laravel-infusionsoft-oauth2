<?php

namespace Djaxho\LaravelInfusionsoftOauth2;

use Illuminate\Support\ServiceProvider;

class LaravelInfusionsoftOauth2ServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    { 
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // use this if your package has views
        $this->loadViewsFrom(
            realpath(__DIR__.'/resources/views'), 'laravel-infusionsoft-oauth2'
        );

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/laravel-infusionsoft-oauth2'),
        ]);

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        
        $this->publishes([
            __DIR__.'/config/laravel-infusionsoft-oauth2.php' => config_path('laravel-infusionsoft-oauth2.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Infusionsoft::class, function ($app) {

            $tokenTable = config('laravel-infusionsoft-oauth2.ISDK_API_TOKENTABLE');

            $infusionsoft = new Infusionsoft([
                'clientId' => config('laravel-infusionsoft-oauth2.ISDK_API_CLIENTID'),
                'clientSecret' => config('laravel-infusionsoft-oauth2.ISDK_API_CLIENTSECRET'),
                'redirectUri' => config('laravel-infusionsoft-oauth2.ISDK_API_REDIRECT'),
                'debug' => config('infusionsoft.debug'),
                'tokenTable' => $tokenTable
            ]);

            // Set TokenManager
            $builder = $app['db']->table($tokenTable);
            $tokenManager = new TokenManager($builder);
            $infusionsoft->setTokenManager($tokenManager);

            // Set Token
            if ($infusionsoft->hasToken()) {
                $token = $infusionsoft->retrieveToken();
                $infusionsoft->setToken($token);
            }

            return $infusionsoft;
        });
    }
}