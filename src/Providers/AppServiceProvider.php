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
        $mainReviews = Reviews::all() ?? collect([]);
        View::share('reviewsSite', $mainReviews);

        $mainAdvantag = Advantag::all() ?? collect([]);
        View::share('advantagSite', $mainAdvantag);

        $mainMenu = Menu::all() ?? collect([]);
        View::share('menusSite', $mainMenu);

        $mainFaq = Faq::all() ?? collect([]);
        View::share('mainFaq', $mainFaq);

        $settingsData = siteSetting::find(1) ?? collect([]);
        View::share('settingsData', $settingsData);

        $server = Server::all() ?? collect([]);
        View::share('server', $server);

        $tgData = settingNotices::find(1) ?? collect([]);
        View::share('tgData', $tgData);

        // Тарифы
        $tariffSettings = TariffSettings::find(1) ?? collect([]);
        View::share('tariffSettings', $tariffSettings);

        // Скидка количество прокси
        $countProxyGeneralDiscounts = CountProxyDiscount::where('type', 'general')->get() ?? collect([]);
        View::share('countProxyGeneralDiscounts', $countProxyGeneralDiscounts);

        $countProxyPrivateDiscounts = CountProxyDiscount::where('type', 'private')->get() ?? collect([]);
        View::share('countProxyPrivateDiscounts', $countProxyPrivateDiscounts);

        $countProxyAllDiscounts = CountProxyDiscount::where('type', 'all')->get() ?? collect([]);
        View::share('countProxyAllDiscounts', $countProxyAllDiscounts);

        // Скидка количество дней
        $countDaysGeneralDiscounts = CountDaysDiscount::where('type', 'general')->get() ?? collect([]);
        View::share('countDaysGeneralDiscounts', $countDaysGeneralDiscounts);

        $countDaysPrivateDiscounts = CountDaysDiscount::where('type', 'private')->get() ?? collect([]);
        View::share('countDaysPrivateDiscounts', $countDaysPrivateDiscounts);

        $countDaysAllDiscounts = CountDaysDiscount::where('type', 'all')->get() ?? collect([]);
        View::share('countDaysAllDiscounts', $countDaysAllDiscounts);

        $partners = Partner::all() ?? collect([]);
        View::share('partners', $partners);
    }
}
