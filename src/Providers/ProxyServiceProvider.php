<?php

namespace Ssda1\proxies\Providers;

use Ssda1\proxies\Console\Commands\EndProxyCron;
use Ssda1\proxies\Console\Commands\SendProxyExpirationNotifications;
use Ssda1\proxies\Console\Commands\SetWebhookCommand;
use Ssda1\proxies\Service\BetatransferService;
use Ssda1\proxies\Service\CapitalistService;
use Ssda1\proxies\Service\ExportPortsService;
use Ssda1\proxies\Service\FreeKassaService;
use Ssda1\proxies\Service\KrakenService;
use Ssda1\proxies\Service\ProcessLogService;
use Ssda1\proxies\Service\ProxyGlobalService;
use Ssda1\proxies\Service\ProxyStatusService;
use Ssda1\proxies\Service\QiwiService;
use Ssda1\proxies\Service\ServerStatusService;
use Ssda1\proxies\Service\UsdtcheckerService;
use Ssda1\proxies\Http\Middleware\RedirectIfProblematicSubscription;
use Ssda1\proxies\Http\Middleware\SetLanguage;

use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Routing\Router;

class ProxyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(AppServiceProvider::class);
        $this->app->register(HelpersLoaderProvider::class);
        $this->app->register(RouteServiceProvider::class);

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
    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'proxies');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'proxies');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $router->middlewareGroup('web', [
            SetLanguage::class,
        ]);

        $router->aliasMiddleware('subscription', RedirectIfProblematicSubscription::class);

        $this->app->booted(function () {
            $this->loadCss(url(__DIR__.'/../resources/css/styles.css'));
        });

        $this->publishes([
            __DIR__.'/public' => public_path('/../public'),
        ], 'proxies-public');

        if ($this->app->runningInConsole()) {
            $this->commands([
                EndProxyCron::class,
                SendProxyExpirationNotifications::class,
                SetWebhookCommand::class
            ]);
        }
    }
}
