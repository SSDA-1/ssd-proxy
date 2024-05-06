<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$templates = 'templates.' . (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory().'.';


Route::get('/run-proxy-command', function () {
    $exitCode = Artisan::call('proxies:send-emails');

    // Дополнительные действия после выполнения команды, если нужны

    return 'Команда успешно выполнена';
});
Route::get('/run-proxy-command-2', function () {
    $output = Artisan::call('demo:cron');

    // Получаем результат выполнения команды
    $output = Artisan::output();

    return $output;
});


Route::get('/locale/{language}', function ($language) {
    session(['locale' => $language]);

    return redirect()->back();
})->name('locale');

Route::post('/get/endpay', [Ssda1\proxies\Http\Controllers\PaymentController::class, 'PaymentBalanceDoneWebhook'])->name('PaymentBalanceDoneWebhook')->withoutMiddleware(['web', 'csrf']);

Route::post('/telegram/webhook', [Ssda1\proxies\Http\Controllers\TelegramController::class, 'webhook'])->middleware('throttle:60,1')
->name('telegram.webhook')
->withoutMiddleware(['web', 'csrf']);
Route::post('/usdtchecker/webhook', [Ssda1\proxies\Http\Controllers\PaymentController::class, 'webhookUSDTchecker'])->name('usdtchecker.webhook')->withoutMiddleware(['web', 'csrf']); // отключаем middleware для проверки CSRF токена; ->middleware('throttle:60,1')
Route::post('/capitalist/interaction', [Ssda1\proxies\Http\Controllers\PaymentController::class, 'interactionСapitalist'])->name('capitalist.webhook')->withoutMiddleware(['web', 'csrf']); // отключаем middleware для проверки CSRF токена; ->middleware('throttle:60,1')
Route::get('/usdtchecker/fail', [Ssda1\proxies\Http\Controllers\PaymentController::class, 'checkUSDTfail']);
// Route::get('/usdtchecker/success', [Ssda1\proxies\Http\Controllers\PaymentController::class, 'checkUSDTsuccess');
Route::get('/usdtchecker/check/{id}', [Ssda1\proxies\Http\Controllers\PaymentController::class, 'chackUSDTStatus']);
Route::get('/usdtchecker/success', [Ssda1\proxies\Http\Controllers\PaymentController::class, 'chackUSDTSuccess']);

Route::get('trouble-with-payment', function () {
    return view('proxies::trouble-with-payment');
});

Route::get('/fetch/proxy/changeip/{proxy}/{proxy2}', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'changeIP'])->name('changeGetIP');

Route::get('invoice/{id}', [Ssda1\proxies\Http\Controllers\TelegramController::class, 'index'])->name('invoice');

Route::post('check/key', [Ssda1\proxies\Http\Controllers\SsdaController::class, 'licenseKey'])->name('check.key');

// Telega
Route::get('/auth/telegram/{code}', [Ssda1\proxies\Http\Controllers\UserController::class, 'processTelegrammAuth'])->name('processTelegrammAuth');

Route::group(['middleware' => ['subscription']], function () {
    Route::get('/', function () {
        $settingModel = Ssda1\proxies\Models\SettingKraken::find(1);

        $servers = Ssda1\proxies\Models\Server::all();
        $serversCountry = $servers->pluck('country')->toArray();

        return view('proxies::home', [
            'settingModel' => $settingModel,
            'serversCountry' => $serversCountry
        ]);
    })->name('home');

    Route::get('rules', [Ssda1\proxies\Http\Controllers\RulesController::class, 'show'])->name('rules');


    Route::get('/blog', [Ssda1\proxies\Http\Controllers\NewsController::class, 'blog'])->name('blog');
    Route::get('/blog/{id}', [Ssda1\proxies\Http\Controllers\NewsController::class, 'blogShow'])->name('blogShow')->where('id', '[0-9]+');
    Route::get('/reviews', [Ssda1\proxies\Http\Controllers\ReviewsController::class, 'reviews'])->name('reviews');
    Route::get('/faq', [Ssda1\proxies\Http\Controllers\FaqController::class, 'faq'])->name('faq');


    Auth::routes();

});

Route::group(['middleware' => ['auth']], function () use ($templates) {
    Route::post('/fetch/save/email', [Ssda1\proxies\Http\Controllers\UserController::class, 'saveEmail'])->name('saveEmail');

    Route::get('lk', function () {
        return view('proxies::admin.lk.index');
    })->name('lk');
    Route::get('buy-proxy', function () {
        $settingModel = Ssda1\proxies\Models\SettingKraken::find(1);

        $servers = Ssda1\proxies\Models\Server::all();
        $serversCountry = $servers->pluck('country')->toArray();

        return view('proxies::admin.lk.buy-proxy', [
            'settingModel' => $settingModel,
            'serversCountry' => $serversCountry
        ]);
    });
    Route::get('replenishment', function () {
        return view('proxies::admin.lk.replenishment');
    });
    Route::get('help', function () use ($templates) {
        return view('proxies::' . $templates . 'pages.support.sups');
    });
    Route::get('training-center', function () {
        return view('proxies::admin.lk.training-center');
    });
    Route::get('partners', function () {
        return view('proxies::admin.lk.partners');
    });


    Route::get('referral', [Ssda1\proxies\Http\Controllers\ReferralController::class, 'index']);

    Route::get('control-panel', [Ssda1\proxies\Http\Controllers\ControlPanelController::class, 'index']);
    Route::post('status-proxy', [Ssda1\proxies\Http\Controllers\ControlPanelController::class, 'statusProxy']);

    Route::post('/fetch/buy', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'HomeBuyProxy'])->name('buyFetch');
    Route::post('/fetch/discount', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'HomeBuyProxyDiscount'])->name('buyFetch.discount');

    Route::post('/fetch/promocode', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'PromocodeCheck'])->name('promocode');

    Route::post('/fetch/proxy/restart/{id}', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'restartModem'])->name('restartModem');
    Route::post('/fetch/proxy/autopay/{id}', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'autopayProxy'])->name('autopayProxy'); // Автопродление
    Route::post('/fetch/proxy/changeip/{proxy}/{proxy2}', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'changeIP'])->name('changeIP');

    Route::post('/fetch/pay/balance', [Ssda1\proxies\Http\Controllers\PaymentController::class, 'PaymentPlusMoney'])->name('PaymentPlusMoney');
    Route::post('/fetch/send/payment', [Ssda1\proxies\Http\Controllers\PaymentController::class, 'sendPayment'])->name('sendPayment');
    // Завершение пополнения
    Route::get('/balancedone/{id}/{payment}', [Ssda1\proxies\Http\Controllers\PaymentController::class, 'PaymentBalanceDone'])->name('PaymentBalanceDone');

    Route::post('/fetch/save/proxy', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'ControlSaveEditProxy'])->name('saveEditControl');
    Route::post('/fetch/save/user', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'ControlSaveUser'])->name('saveUserControl');

    Route::post('/fetch/extend/proxy', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'ControlExtendProxy'])->name('controlExtend');

    // массовое редактирование
    Route::post('/fetch/multi/extend/proxy', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'multiControlExtendProxy'])->name('multiControlExtendProxy');
    Route::post('/fetch/multi/change/ip', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'multiChangeIP'])->name('multiChangeIP');
    Route::post('/fetch/multi/change/time', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'multiChangeTimeIP'])->name('multiChangeTimeIP');
    Route::post('/fetch/multi/download/time', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'multiDownload'])->name('multiDownload');

    Route::get('/proxy/download/{id}', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'download'])->name('proxy.download');

    Route::get('/support', [Ssda1\proxies\Http\Controllers\SupportController::class, 'userPageSupports'])->name('supportsSite');
    Route::get('/newsupport', [Ssda1\proxies\Http\Controllers\SupportController::class, 'userPageSupportsNew'])->name('supportsSiteNew');
    Route::get('/support/{id}', [Ssda1\proxies\Http\Controllers\SupportController::class, 'userPageSupport'])->name('supportSite')->where('id', '[0-9]+');
    Route::post('/fetch/support/sendsupport', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'sendSupportMass'])->name('sendSupportMass');
});


Route::group(['middleware' => ['auth', 'subscription', 'permission:admin-panel']], function () {
    // ,'subscription'

    Route::resources([
        'roles' => 'Ssda1\proxies\Http\Controllers\RoleController',
        'users' => 'Ssda1\proxies\Http\Controllers\UserController',
        'news' => 'Ssda1\proxies\Http\Controllers\NewsController',
        'menu' => 'Ssda1\proxies\Http\Controllers\MenuController',
        'proxy' => 'Ssda1\proxies\Http\Controllers\ProxyController',
        'logs' => 'Ssda1\proxies\Http\Controllers\ProcessLogController',
        'promocodes' => 'Ssda1\proxies\Http\Controllers\PromocodeController',
        'countdaysdiscount' => 'Ssda1\proxies\Http\Controllers\CountDaysDiscountController',
        'countproxydiscount' => 'Ssda1\proxies\Http\Controllers\CountProxyDiscountController',
        'tariffsettings' => 'Ssda1\proxies\Http\Controllers\TariffSettingsController',
        'countpairsproxydiscount' => 'Ssda1\proxies\Http\Controllers\CountPairsProxyDiscountController',
    ]);
    Route::get('/users/search', [Ssda1\proxies\Http\Controllers\UserController::class, 'search'])->name('users.search');
    Route::get('/proxy/ports/search', [Ssda1\proxies\Http\Controllers\ProxyController::class, 'searchPorts'])->name('proxy.ports.search');
    Route::post('/mode', [Ssda1\proxies\Http\Controllers\UserController::class, 'mode'])->name('mode');
    Route::post('/sidebarmode', [Ssda1\proxies\Http\Controllers\UserController::class, 'sidebarMode'])->name('sidebarmode');
    Route::resource('reviews-adm', 'Ssda1\proxies\Http\Controllers\ReviewsController')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('faq-adm', 'Ssda1\proxies\Http\Controllers\FaqController')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('port', 'Ssda1\proxies\Http\Controllers\ModemController')->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::get('notice', function () {
        return view('proxies::admin.notice.index');
    });

    Route::prefix('admin')->group(function () {
        // РЕФЕРАЛКА
        Route::get('ref-stat', function () {
            return view('proxies::admin.referrals.stat');
        });
        // ===========
        Route::resource('support', 'Ssda1\proxies\Http\Controllers\SupportController')->only(['index', 'show', 'create', 'store', 'update', 'destroy']);
        Route::resource('advantag', 'Ssda1\proxies\Http\Controllers\AdvantagsResurceController')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('partners', 'Ssda1\proxies\Http\Controllers\PartnerController')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('servers', 'Ssda1\proxies\Http\Controllers\ServerController')->only(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('withdrawalrequest', 'Ssda1\proxies\Http\Controllers\WithdrawalRequestController');
        Route::get('statistics/withdrawalrequest', [Ssda1\proxies\Http\Controllers\ReferralController::class, 'statistic']);
        Route::resource('rules', 'Ssda1\proxies\Http\Controllers\RulesController')->only(['index', 'store', 'edit', 'update']);
    });

    Route::get('admin-panel', function () {
        $servers = Ssda1\proxies\Models\Server::all();
        $chart = (new \Ssda1\proxies\Http\Controllers\ChartController())->portsByAllTime()->portsByYear()->portsByMonth();
        $statistics = (new \Ssda1\proxies\Http\Controllers\ChartController())->statisticSell();
        $countModems = (new \Ssda1\proxies\Http\Controllers\ModemController())->getCountModems();
        $countModemsByFilledUsers = (new \Ssda1\proxies\Http\Controllers\ModemController())->getCountModemsByFilledUsers();
        $countKraken = (new \Ssda1\proxies\Http\Controllers\ServerController)->getCountKraken();
        $countProxies = (new \Ssda1\proxies\Http\Controllers\ProxyController())->getProxies();
        $getProxiesPause = (new \Ssda1\proxies\Http\Controllers\ProxyController())->getProxiesPause();
        $totalAmountKraken = (new \Ssda1\proxies\Http\Controllers\SsdaController())->getInfoFromSsda()->getAmountKraken();
        $startOfSubscription = (new \Ssda1\proxies\Http\Controllers\SsdaController())->getInfoFromSsda()->getStartOfSubscription();
        $endOfSubscription = (new \Ssda1\proxies\Http\Controllers\SsdaController())->getInfoFromSsda()->getEndOfSubscriptionFormatted();
        $nameOfSubscription = (new \Ssda1\proxies\Http\Controllers\SsdaController())->getInfoFromSsda()->getNameOfSubscription();
        return view('proxies::admin.home', compact(
            'chart',
            'statistics',
            'countKraken',
            'countModems',
            'countModemsByFilledUsers',
            'countProxies',
            'getProxiesPause',
            'totalAmountKraken',
            'endOfSubscription',
            'nameOfSubscription',
            'startOfSubscription',
            'servers'
        ));
    });
    Route::post('admin-panel', [Ssda1\proxies\Http\Controllers\ChartController::class, 'portsByInputValue'])->name('chartValue');
    Route::post('/fetch/statisticsell/day', [Ssda1\proxies\Http\Controllers\ChartController::class, 'statisticSellDay'])->name('statisticSellDay');
    Route::post('/fetch/statisticsell/month', [Ssda1\proxies\Http\Controllers\ChartController::class, 'statisticSellMonth'])->name('statisticSellMonth');
    Route::post('/fetch/statisticsell/year', [Ssda1\proxies\Http\Controllers\ChartController::class, 'statisticSellYear'])->name('statisticSellYear');

    Route::post('/fetch/export/ports', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'exportPorts'])->name('exportPorts');
    Route::post('/fetch/export/proxy', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'exportProxy'])->name('exportProxy');
    Route::post('/fetch/export/users', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'exportUsers'])->name('exportUsers');

    Route::post('/admin/fetch/server/getintmod/{id}', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'getIntMod'])->name('getIntMod');
    Route::post('/admin/fetch/proxy/addtime', [Ssda1\proxies\Http\Controllers\AjaxController::class, 'addTimeProxy'])->name('addTimeProxy');
    Route::post('/admin/fetch/support/sendsupport', [Ssda1\proxies\Http\Controllers\SupportController::class, 'sendSupportMassAdmin'])->name('sendSupportMassAdmin');
    Route::post('/admin/fetch/support/closesupp', [Ssda1\proxies\Http\Controllers\SupportController::class, 'closeSupp'])->name('closeSupp');

    Route::get('/admin/proxy/setting', [Ssda1\proxies\Http\Controllers\ProxyController::class, 'setting'])->name('proxySetting');
    Route::get('/admin/settings', [Ssda1\proxies\Http\Controllers\SiteSettingsController::class, 'index'])->name('allSettingSite');

    Route::post('/admin/fetch/payment/settings', [Ssda1\proxies\Http\Controllers\PaymentController::class, 'PaymentSettingsAdmin'])->name('PaymentSettingsAdmin');

    Route::post('/admin/fetch/setting/notice', [Ssda1\proxies\Http\Controllers\SiteSettingsController::class, 'noticeSettings'])->name('NoticeSettingsAdmin');
    Route::post('/admin/fetch/setting/integration', [Ssda1\proxies\Http\Controllers\SettingKrakenController::class, 'integrationSave'])->name('integrationSave');
    Route::post('/admin/fetch/setting/basic', [Ssda1\proxies\Http\Controllers\SettingKrakenController::class, 'basicSave'])->name('basicSave');
    Route::post('/admin/fetch/setting/sale', [Ssda1\proxies\Http\Controllers\SettingKrakenController::class, 'saleSave'])->name('saleSave');
    Route::post('/admin/fetch/balace/change', [Ssda1\proxies\Http\Controllers\HistoryOperationController::class, 'balanceChanges'])->name('balanceChanges');
    Route::post('/admin/fetch/balace/allsettings', [Ssda1\proxies\Http\Controllers\SiteSettingsController::class, 'allSettingsSiteSave'])->name('allSettingsSiteSave');
    Route::post('/admin/fetch/balace/refsettings', [Ssda1\proxies\Http\Controllers\SiteSettingsController::class, 'referallSettingsSiteSave'])->name('referallSettingsSiteSave');
    Route::post('/admin/fetch/setting/ceo', [Ssda1\proxies\Http\Controllers\SiteSettingsController::class, 'ceoSettingsSiteSave'])->name('ceoSettingsSiteSave');
    Route::post('/admin/fetch/setting/delimage', [Ssda1\proxies\Http\Controllers\SiteSettingsController::class, 'delimageSetting'])->name('delimageSetting');
    Route::controller(Ssda1\proxies\Http\Controllers\TemplateController::class)->group(function () {
        Route::get('/template-management', 'index')->name('template-management');
        Route::get('/template-management/create', 'createTemplate')->name('create-template');
        Route::get('/template-management/{id}', 'showTemplate')->name('show-template')->where('id', '[0-9]+');
        Route::get('/template-management/buy/{id}', 'buyTemplate')->name('buy-template')->where('id', '[0-9]+');
        Route::post('/template-management/create', 'storeTemplate')->name('store-template');
        Route::post('/template-management/change/{id}', 'changeTemplate')->name('change-template')->where('id', '[0-9]+');
    });
});

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');

    return "Кэш очищен.";
});

Route::get('/cron', function () {
    Artisan::call('proxies:send-emails');

    return "Крон.";
});
