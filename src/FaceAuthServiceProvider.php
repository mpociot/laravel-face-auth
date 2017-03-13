<?php

namespace Mpociot\FaceAuth;

use Illuminate\Support\ServiceProvider;

class FaceAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/face_auth.php' => config_path('face_auth.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/face_auth.php', 'face_auth');

        $this->app->make('auth')->provider('faceauth', function ($app, array $config) {
            return new FaceAuthUserProvider($app['hash'], $config['model']);
        });
    }
}
