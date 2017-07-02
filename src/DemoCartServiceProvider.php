<?php

namespace Nikitakiselev\DemoCart;

use Illuminate\Support\ServiceProvider;
use Nikitakiselev\DemoCart\Storage\StorageManager;

class DemoCartServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/demo-cart.php' => config_path('demo-cart.php'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/demo-cart.php', 'demo-cart'
        );

        $this->registerCartStorage();

        $this->app->singleton(Cart::class, function ($app) {
            return new Cart($app['session'], $app['cart.store']);
        });

        $this->app->alias(Cart::class, 'cart');
    }

    protected function registerCartStorage()
    {
        $this->app->singleton('cart.store', function ($app) {
            return (new StorageManager($app))->driver();
        });
    }
}
