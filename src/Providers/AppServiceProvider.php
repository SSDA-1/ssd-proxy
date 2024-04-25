<?php

namespace ssda1\proxies\Providers;

use ssda1\proxies\Models\Faq;
use ssda1\proxies\Models\Menu;
use ssda1\proxies\Models\Server;
use ssda1\proxies\Models\Partner;
use ssda1\proxies\Models\Reviews;
use ssda1\proxies\Models\Advantag;
use ssda1\proxies\Models\siteSetting;
use ssda1\proxies\Models\SettingNotices;
use ssda1\proxies\Models\TariffSettings;
use ssda1\proxies\Models\CountDaysDiscount;
use ssda1\proxies\Models\CountProxyDiscount;

use Jenssegers\Date\Date;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //session(['locale' => 'ru']);

        $mainReviews = Reviews::all();
        View::share('reviewsSite', $mainReviews);

        $mainAdvantag = Advantag::all();
        View::share('advantagSite', $mainAdvantag);

        $mainMenu = Menu::all();
        View::share('menusSite', $mainMenu);

        $mainFaq = Faq::all();
        View::share('mainFaq', $mainFaq);

        $settingsData = siteSetting::find(1);
        View::share('settingsData', $settingsData);

        $server = Server::all();
        View::share('server', $server);

        $tgData = settingNotices::find(1);
        View::share('tgData', $tgData);

        // Тарифы
        $tariffSettings = TariffSettings::find(1);
        View::share('tariffSettings', $tariffSettings);

        // Скидка количество прокси
        $countProxyGeneralDiscounts = CountProxyDiscount::where('type', 'general')->get();
        View::share('countProxyGeneralDiscounts', $countProxyGeneralDiscounts);

        $countProxyPrivateDiscounts = CountProxyDiscount::where('type', 'private')->get();
        View::share('countProxyPrivateDiscounts', $countProxyPrivateDiscounts);

        $countProxyAllDiscounts = CountProxyDiscount::where('type', 'all')->get();
        View::share('countProxyAllDiscounts', $countProxyAllDiscounts);

        // Скидка количество дней
        $countDaysGeneralDiscounts = CountDaysDiscount::where('type', 'general')->get();
        View::share('countDaysGeneralDiscounts', $countDaysGeneralDiscounts);

        $countDaysPrivateDiscounts = CountDaysDiscount::where('type', 'private')->get();
        View::share('countDaysPrivateDiscounts', $countDaysPrivateDiscounts);

        $countDaysAllDiscounts = CountDaysDiscount::where('type', 'all')->get();
        View::share('countDaysAllDiscounts', $countDaysAllDiscounts);

        $partners = Partner::all();
        View::share('partners', $partners);

        // Date::setLocale(config('app.locale'));

        // View::composer('*', function ($view) {
        //     $view->with(compact('mainMenu'));
        // });
    }
}
