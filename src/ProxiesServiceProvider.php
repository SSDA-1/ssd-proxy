<?php

namespace Ssda1\proxies;

use Illuminate\Support\ServiceProvider;

class ProxiesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void 
     */
    public function boot()
    {
        // Регистрация консольных команд
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Ssda1\proxies\Console\Commands\UpdatePackageCommand::class,
            ]);
        }
    }
}