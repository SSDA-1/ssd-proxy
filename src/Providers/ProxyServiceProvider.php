<?php

namespace ssd\proxies\Providers;

use ssd\proxies\Service\BetatransferService;
use ssd\proxies\Service\CapitalistService;
use ssd\proxies\Service\ExportPortsService;
use ssd\proxies\Service\FreeKassaService;
use ssd\proxies\Service\KrakenService;
use ssd\proxies\Service\ProcessLogService;
use ssd\proxies\Service\ProxyGlobalService;
use ssd\proxies\Service\ProxyStatusService;
use ssd\proxies\Service\QiwiService;
use ssd\proxies\Service\ServerStatusService;
use ssd\proxies\Service\UsdtcheckerService;

use Illuminate\Support\ServiceProvider;

class ProxyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('betatransfer-service', function () {
            return new BetatransferService();
        });

        $this->app->bind('capitalist-service', function () {
            return new CapitalistService();
        });

        $this->app->bind('export-ports-service', function () {
            return new ExportPortsService();
        });

        $this->app->bind('free-kassa-service', function () {
            return new FreeKassaService();
        });

        $this->app->bind('kraken-service', function () {
            return new KrakenService();
        });

        $this->app->bind('process-log-service', function () {
            return new ProcessLogService();
        });

        $this->app->bind('proxy-global-service', function () {
            return new ProxyGlobalService();
        });

        $this->app->bind('proxy-status-service', function () {
            return new ProxyStatusService();
        });

        $this->app->bind('qiwi-service', function () {
            return new QiwiService();
        });

        $this->app->bind('server-status-service', function () {
            return new ServerStatusService();
        });

        $this->app->bind('usdtchecker-service', function () {
            return new UsdtcheckerService();
        });

        $this->app->bind('usdtchecker-service', function () {
            return new UsdtcheckerService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //$this->loadCommandsFrom(__DIR__.'/../Console');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        //$this->loadControllersFrom(__DIR__.'/../Http/Controllers');
        //$this->loadMiddlewareFrom(__DIR__.'/../Http/Middleware');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'proxies');
       // $this->loadModelsFrom(__DIR__.'/../Models');
        //$this->loadNotificationsFrom(__DIR__.'/../Notifications');
        //$this->loadProvidersFrom(__DIR__.'/../Providers');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'proxies');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../lang' => resource_path('lang/vendor/proxies'),
        ], 'translations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/proxies'),
        ]);

        $this->publishes([
            __DIR__.'/../config/license.php' => config_path('license.php'),
        ]);

        $this->publishes([
            __DIR__.'/../public' => public_path(),
            'public'
        ]);
    }
}
