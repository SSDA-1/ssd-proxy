<?php

namespace ssda1\proxies\Providers;

use ssda1\proxies\Service\BetatransferService;
use ssda1\proxies\Service\CapitalistService;
use ssda1\proxies\Service\ExportPortsService;
use ssda1\proxies\Service\FreeKassaService;
use ssda1\proxies\Service\KrakenService;
use ssda1\proxies\Service\ProcessLogService;
use ssda1\proxies\Service\ProxyGlobalService;
use ssda1\proxies\Service\ProxyStatusService;
use ssda1\proxies\Service\QiwiService;
use ssda1\proxies\Service\ServerStatusService;
use ssda1\proxies\Service\UsdtcheckerService;

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
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

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
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'proxies');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'proxies');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}
