<?php

namespace Ssda1\proxies\Providers;

use Ssda1\proxies\Models\Faq;
use Ssda1\proxies\Models\Menu;
use Ssda1\proxies\Models\Server;
use Ssda1\proxies\Models\Partner;
use Ssda1\proxies\Models\Reviews;
use Ssda1\proxies\Models\Advantag;
use Ssda1\proxies\Models\siteSetting;
use Ssda1\proxies\Models\SettingNotices;
use Ssda1\proxies\Models\TariffSettings;
use Ssda1\proxies\Models\CountDaysDiscount;
use Ssda1\proxies\Models\CountProxyDiscount;

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
        $mainReviews = collect([]);
        if (Schema::hasTable('reviews')) {
            $mainReviews = Reviews::all();
        }
        View::share('reviewsSite', $mainReviews);

        $mainAdvantag = collect([]);
        if (Schema::hasTable('advantags')) {
            $mainAdvantag = Advantag::all();
        }
        View::share('advantagSite', $mainAdvantag);

        $mainMenu = collect([]);
        if (Schema::hasTable('menus')) {
            $mainMenu = Menu::all();
        }
        View::share('menusSite', $mainMenu);

        $mainFaq = collect([]);
        if (Schema::hasTable('faqs')) {
            $mainFaq = Faq::all();
        }
        View::share('mainFaq', $mainFaq);

        $settingsData = collect([]);
        if (Schema::hasTable('site_settings')) {
            $settingsData = siteSetting::find(1) ?? collect([]);
        }
        View::share('settingsData', $settingsData);

        $server = collect([]);
        if (Schema::hasTable('servers')) {
            $server = Server::all();
        }
        View::share('server', $server);

        $tgData = collect([]);
        if (Schema::hasTable('setting_notices')) {
            $tgData = settingNotices::find(1) ?? collect([]);
        }
        View::share('tgData', $tgData);

        // Тарифы
        $tariffSettings = collect([]);
        if (Schema::hasTable('tariff_settings')) {
            $tariffSettings = TariffSettings::find(1) ?? collect([]);
        }
        View::share('tariffSettings', $tariffSettings);

        // Скидка количество прокси
        $countProxyGeneralDiscounts = collect([]);
        if (Schema::hasTable('count_proxy_discounts')) {
            $countProxyGeneralDiscounts = CountProxyDiscount::where('type', 'general')->get();
        }
        View::share('countProxyGeneralDiscounts', $countProxyGeneralDiscounts);

        $countProxyPrivateDiscounts = collect([]);
        if (Schema::hasTable('count_proxy_discounts')) {
            $countProxyPrivateDiscounts = CountProxyDiscount::where('type', 'private')->get();
        }
        View::share('countProxyPrivateDiscounts', $countProxyPrivateDiscounts);

        $countProxyAllDiscounts = collect([]);
        if (Schema::hasTable('count_proxy_discounts')) {
            $countProxyAllDiscounts = CountProxyDiscount::where('type', 'all')->get();
        }
        View::share('countProxyAllDiscounts', $countProxyAllDiscounts);

        // Скидка количество дней
        $countDaysGeneralDiscounts = collect([]);
        if (Schema::hasTable('count_days_discounts')) {
            $countDaysGeneralDiscounts = CountDaysDiscount::where('type', 'general')->get();
        }
        View::share('countDaysGeneralDiscounts', $countDaysGeneralDiscounts);

        $countDaysPrivateDiscounts = collect([]);
        if (Schema::hasTable('count_days_discounts')) {
            $countDaysPrivateDiscounts = CountDaysDiscount::where('type', 'private')->get();
        }
        View::share('countDaysPrivateDiscounts', $countDaysPrivateDiscounts);

        $countDaysAllDiscounts = collect([]);
        if (Schema::hasTable('count_days_discounts')) {
            $countDaysAllDiscounts = CountDaysDiscount::where('type', 'all')->get();
        }
        View::share('countDaysAllDiscounts', $countDaysAllDiscounts);

        $partners = collect([]);
        if (Schema::hasTable('partners')) {
            $partners = Partner::all();
        }
        View::share('partners', $partners);
    }
}
