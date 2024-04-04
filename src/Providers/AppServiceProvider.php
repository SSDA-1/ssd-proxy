<?php

namespace ssd\proxies\Providers;

use ssd\proxies\Models\Faq;
use ssd\proxies\Models\Menu;
use ssd\proxies\Models\Server;
use ssd\proxies\Models\Partner;
use ssd\proxies\Models\Reviews;
use ssd\proxiesApp\Models\Advantag;
use ssd\proxies\Models\siteSetting;
use ssd\proxies\Models\SettingNotices;
use ssd\proxies\Models\TariffSettings;
use ssd\proxies\Models\CountDaysDiscount;
use ssd\proxies\Models\CountProxyDiscount;

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
