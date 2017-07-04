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
        // dd(Infusionsoft::class);
        $this->app->singleton(Infusionsoft::class, function($app) {
            return new InfusionsoftClass;
        });
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    // public function provides()
    // {
    //     return [Infusionsoft::class];
    // }
}