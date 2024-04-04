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

$templates = 'templates.' . (new ssd\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory().'.';


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

/*Route::get('/modelControl/{name}', function ($name) {
    Artisan::call('make:model '.$name.' -cm');

    return "Контроллер $name и модель созданы ";
});*/
/*Route::get('/migration/{name}', function ($name) {
    Artisan::call('make:migration '.$name);

    return "Контроллер $name и модель созданы ";
});*/
/*Route::get('/migrate', function () {
    Artisan::call('migrate');

    return "Миграция выполнена";
});*/

Route::post('/get/endpay', [ssd\proxies\Http\Controllers\PaymentController::class, 'PaymentBalanceDoneWebhook'])->name('PaymentBalanceDoneWebhook')->withoutMiddleware(['web', 'csrf']);

Route::post('/telegram/webhook', [ssd\proxies\Http\Controllers\TelegramController::class, 'webhook'])->middleware('throttle:60,1')
->name('telegram.webhook')
->withoutMiddleware(['web', 'csrf']);
Route::post('/usdtchecker/webhook', [ssd\proxies\Http\Controllers\PaymentController::class, 'webhookUSDTchecker'])->name('usdtchecker.webhook')->withoutMiddleware(['web', 'csrf']); // отключаем middleware для проверки CSRF токена; ->middleware('throttle:60,1')
Route::post('/capitalist/interaction', [ssd\proxies\Http\Controllers\PaymentController::class, 'interactionСapitalist'])->name('capitalist.webhook')->withoutMiddleware(['web', 'csrf']); // отключаем middleware для проверки CSRF токена; ->middleware('throttle:60,1')
Route::get('/usdtchecker/fail', [ssd\proxies\Http\Controllers\PaymentController::class, 'checkUSDTfail']);
// Route::get('/usdtchecker/success', [ssd\proxies\Http\Controllers\PaymentController::class, 'checkUSDTsuccess');
Route::get('/usdtchecker/check/{id}', [ssd\proxies\Http\Controllers\PaymentController::class, 'chackUSDTStatus']);
Route::get('/usdtchecker/success', [ssd\proxies\Http\Controllers\PaymentController::class, 'chackUSDTSuccess']);

Route::get('trouble-with-payment', function () {
    return view('trouble-with-payment');
});

Route::get('/fetch/proxy/changeip/{proxy}/{proxy2}', [ssd\proxies\Http\Controllers\AjaxController::class, 'changeIP'])->name('changeGetIP');

Route::get('invoice/{id}', [ssd\proxies\Http\Controllers\TelegramController::class, 'index'])->name('invoice');

Route::post('check/key', [ssd\proxies\Http\Controllers\SsdaController::class, 'licenseKey'])->name('check.key');

// Telega
Route::get('/auth/telegram/{code}', [ssd\proxies\Http\Controllers\UserController::class, 'processTelegrammAuth'])->name('processTelegrammAuth');

Route::group(['middleware' => ['subscription']], function () {
    Route::get('/', function () {
        $settingModel = ssd\proxies\Models\SettingKraken::find(1);

        $servers = ssd\proxies\Models\Server::all();
        $serversCountry = $servers->pluck('country')->toArray();

        return view('home', [
            'settingModel' => $settingModel,
            'serversCountry' => $serversCountry
        ]);
    })->name('home');

    Route::get('rules', [ssd\proxies\Http\Controllers\RulesController::class, 'show'])->name('rules');


    Route::get('/blog', [ssd\proxies\Http\Controllers\NewsController::class, 'blog'])->name('blog');
    Route::get('/blog/{id}', [ssd\proxies\Http\Controllers\NewsController::class, 'blogShow'])->name('blogShow')->where('id', '[0-9]+');
    Route::get('/reviews', [ssd\proxies\Http\Controllers\ReviewsController::class, 'reviews'])->name('reviews');
    Route::get('/faq', [ssd\proxies\Http\Controllers\FaqController::class, 'faq'])->name('faq');


    Auth::routes();

});

Route::group(['middleware' => ['auth']], function () use ($templates) {
    Route::post('/fetch/save/email', [ssd\proxies\Http\Controllers\UserController::class, 'saveEmail'])->name('saveEmail');

    Route::get('lk', function () {
        return view('admin.lk.index');
    })->name('lk');
    Route::get('buy-proxy', function () {
        $settingModel = ssd\proxies\Models\SettingKraken::find(1);

        $servers = ssd\proxies\Models\Server::all();
        $serversCountry = $servers->pluck('country')->toArray();

        return view('admin.lk.buy-proxy', [
            'settingModel' => $settingModel,
            'serversCountry' => $serversCountry
        ]);
    });
    Route::get('replenishment', function () {
        return view('admin.lk.replenishment');
    });
    Route::get('help', function () use ($templates) {
        return view($templates. 'pages.support.sups');
    });
    Route::get('training-center', function () {
        return view('admin.lk.training-center');
    });
    Route::get('partners', function () {
        return view('admin.lk.partners');
    });
    

    Route::get('referral', [ssd\proxies\Http\Controllers\ReferralController::class, 'index']);

    Route::get('control-panel', [ssd\proxies\Http\Controllers\ControlPanelController::class, 'index']);
    Route::post('status-proxy', [ssd\proxies\Http\Controllers\ControlPanelController::class, 'statusProxy']);

    Route::post('/fetch/buy', [ssd\proxies\Http\Controllers\AjaxController::class, 'HomeBuyProxy'])->name('buyFetch');
    Route::post('/fetch/discount', [ssd\proxies\Http\Controllers\AjaxController::class, 'HomeBuyProxyDiscount'])->name('buyFetch.discount');

    Route::post('/fetch/promocode', [ssd\proxies\Http\Controllers\AjaxController::class, 'PromocodeCheck'])->name('promocode');

    Route::post('/fetch/proxy/restart/{id}', [ssd\proxies\Http\Controllers\AjaxController::class, 'restartModem'])->name('restartModem');
    Route::post('/fetch/proxy/autopay/{id}', [ssd\proxies\Http\Controllers\AjaxController::class, 'autopayProxy'])->name('autopayProxy'); // Автопродление
    Route::post('/fetch/proxy/changeip/{proxy}/{proxy2}', [ssd\proxies\Http\Controllers\AjaxController::class, 'changeIP'])->name('changeIP');

    Route::post('/fetch/pay/balance', [ssd\proxies\Http\Controllers\PaymentController::class, 'PaymentPlusMoney'])->name('PaymentPlusMoney');
    Route::post('/fetch/send/payment', [ssd\proxies\Http\Controllers\PaymentController::class, 'sendPayment'])->name('sendPayment');
    // Завершение пополнения
    Route::get('/balancedone/{id}/{payment}', [ssd\proxies\Http\Controllers\PaymentController::class, 'PaymentBalanceDone'])->name('PaymentBalanceDone');

    Route::post('/fetch/save/proxy', [ssd\proxies\Http\Controllers\AjaxController::class, 'ControlSaveEditProxy'])->name('saveEditControl');
    Route::post('/fetch/save/user', [ssd\proxies\Http\Controllers\AjaxController::class, 'ControlSaveUser'])->name('saveUserControl');

    Route::post('/fetch/extend/proxy', [ssd\proxies\Http\Controllers\AjaxController::class, 'ControlExtendProxy'])->name('controlExtend');

    // массовое редактирование
    Route::post('/fetch/multi/extend/proxy', [ssd\proxies\Http\Controllers\AjaxController::class, 'multiControlExtendProxy'])->name('multiControlExtendProxy');
    Route::post('/fetch/multi/change/ip', [ssd\proxies\Http\Controllers\AjaxController::class, 'multiChangeIP'])->name('multiChangeIP');
    Route::post('/fetch/multi/change/time', [ssd\proxies\Http\Controllers\AjaxController::class, 'multiChangeTimeIP'])->name('multiChangeTimeIP');
    Route::post('/fetch/multi/download/time', [ssd\proxies\Http\Controllers\AjaxController::class, 'multiDownload'])->name('multiDownload');

    Route::get('/proxy/download/{id}', [ssd\proxies\Http\Controllers\AjaxController::class, 'download'])->name('proxy.download');

    Route::get('/support', [ssd\proxies\Http\Controllers\SupportController::class, 'userPageSupports'])->name('supportsSite');
    Route::get('/newsupport', [ssd\proxies\Http\Controllers\SupportController::class, 'userPageSupportsNew'])->name('supportsSiteNew');
    Route::get('/support/{id}', [ssd\proxies\Http\Controllers\SupportController::class, 'userPageSupport'])->name('supportSite')->where('id', '[0-9]+');
    Route::post('/fetch/support/sendsupport', [ssd\proxies\Http\Controllers\AjaxController::class, 'sendSupportMass'])->name('sendSupportMass');
});


Route::group(['middleware' => ['auth', 'subscription', 'permission:admin-panel']], function () {
    // ,'subscription'

    Route::resources([
        'roles' => 'ssd\proxies\Http\Controllers\RoleController',
        'users' => 'ssd\proxies\Http\Controllers\UserController',
        'news' => 'ssd\proxies\Http\Controllers\NewsController',
        'menu' => 'ssd\proxies\Http\Controllers\MenuController',
        'proxy' => 'ssd\proxies\Http\Controllers\ProxyController',
        'logs' => 'ssd\proxies\Http\Controllers\ProcessLogController',
        'promocodes' => 'ssd\proxies\Http\Controllers\PromocodeController',
        'countdaysdiscount' => 'ssd\proxies\Http\Controllers\CountDaysDiscountController',
        'countproxydiscount' => 'ssd\proxies\Http\Controllers\CountProxyDiscountController',
        'tariffsettings' => 'ssd\proxies\Http\Controllers\TariffSettingsController',
        'countpairsproxydiscount' => 'ssd\proxies\Http\Controllers\CountPairsProxyDiscountController',
    ]);
    Route::get('/users/search', [ssd\proxies\Http\Controllers\UserController::class, 'search'])->name('users.search');
    Route::get('/proxy/ports/search', [ssd\proxies\Http\Controllers\ProxyController::class, 'searchPorts'])->name('proxy.ports.search');
    Route::post('/mode', [ssd\proxies\Http\Controllers\UserController::class, 'mode'])->name('mode');
    Route::post('/sidebarmode', [ssd\proxies\Http\Controllers\UserController::class, 'sidebarMode'])->name('sidebarmode');
    Route::resource('reviews-adm', 'ssd\proxies\Http\Controllers\ReviewsController')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('faq-adm', 'ssd\proxies\Http\Controllers\FaqController')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('port', 'ssd\proxies\Http\Controllers\ModemController')->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::get('notice', function () {
        return view('admin.notice.index');
    });

    Route::prefix('admin')->group(function () {
        // РЕФЕРАЛКА
        Route::get('ref-stat', function () {
            return view('admin.referrals.stat');
        });
        // ===========
        Route::resource('support', 'ssd\proxies\Http\Controllers\SupportController')->only(['index', 'show', 'create', 'store', 'update', 'destroy']);
        Route::resource('advantag', 'ssd\proxies\Http\Controllers\AdvantagsResurceController')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('partners', 'ssd\proxies\Http\Controllers\PartnerController')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('servers', 'ssd\proxies\Http\Controllers\ServerController')->only(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('withdrawalrequest', 'ssd\proxies\Http\Controllers\WithdrawalRequestController');
        Route::get('statistics/withdrawalrequest', [ssd\proxies\Http\Controllers\ReferralController::class, 'statistic']);
        Route::resource('rules', 'ssd\proxies\Http\Controllers\RulesController')->only(['index', 'store', 'edit', 'update']);
    });

    Route::get('admin-panel', function () {
        $servers = ssd\proxies\Models\Server::all();
        $chart = (new \ssd\proxies\Http\Controllers\ChartController())->portsByAllTime()->portsByYear()->portsByMonth();
        $statistics = (new \ssd\proxies\Http\Controllers\ChartController())->statisticSell();
        $countModems = (new \ssd\proxies\Http\Controllers\ModemController())->getCountModems();
        $countModemsByFilledUsers = (new \ssd\proxies\Http\Controllers\ModemController())->getCountModemsByFilledUsers();
        $countKraken = (new \ssd\proxies\Http\Controllers\ServerController)->getCountKraken();
        $countProxies = (new \ssd\proxies\Http\Controllers\ProxyController())->getProxies();
        $getProxiesPause = (new \ssd\proxies\Http\Controllers\ProxyController())->getProxiesPause();
        $totalAmountKraken = (new \ssd\proxies\Http\Controllers\SsdaController())->getInfoFromSsda()->getAmountKraken();
        $startOfSubscription = (new \ssd\proxies\Http\Controllers\SsdaController())->getInfoFromSsda()->getStartOfSubscription();
        $endOfSubscription = (new \ssd\proxies\Http\Controllers\SsdaController())->getInfoFromSsda()->getEndOfSubscriptionFormatted();
        $nameOfSubscription = (new \ssd\proxies\Http\Controllers\SsdaController())->getInfoFromSsda()->getNameOfSubscription();
        return view('admin.home', compact(
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
    Route::post('admin-panel', [ssd\proxies\Http\Controllers\ChartController::class, 'portsByInputValue'])->name('chartValue');
    Route::post('/fetch/statisticsell/day', [ssd\proxies\Http\Controllers\ChartController::class, 'statisticSellDay'])->name('statisticSellDay');
    Route::post('/fetch/statisticsell/month', [ssd\proxies\Http\Controllers\ChartController::class, 'statisticSellMonth'])->name('statisticSellMonth');
    Route::post('/fetch/statisticsell/year', [ssd\proxies\Http\Controllers\ChartController::class, 'statisticSellYear'])->name('statisticSellYear');

    Route::post('/fetch/export/ports', [ssd\proxies\Http\Controllers\AjaxController::class, 'exportPorts'])->name('exportPorts');
    Route::post('/fetch/export/proxy', [ssd\proxies\Http\Controllers\AjaxController::class, 'exportProxy'])->name('exportProxy');
    Route::post('/fetch/export/users', [ssd\proxies\Http\Controllers\AjaxController::class, 'exportUsers'])->name('exportUsers');

    Route::post('/admin/fetch/server/getintmod/{id}', [ssd\proxies\Http\Controllers\AjaxController::class, 'getIntMod'])->name('getIntMod');
    Route::post('/admin/fetch/proxy/addtime', [ssd\proxies\Http\Controllers\AjaxController::class, 'addTimeProxy'])->name('addTimeProxy');
    Route::post('/admin/fetch/support/sendsupport', [ssd\proxies\Http\Controllers\SupportController::class, 'sendSupportMassAdmin'])->name('sendSupportMassAdmin');
    Route::post('/admin/fetch/support/closesupp', [ssd\proxies\Http\Controllers\SupportController::class, 'closeSupp'])->name('closeSupp');

    Route::get('/admin/proxy/setting', [ssd\proxies\Http\Controllers\ProxyController::class, 'setting'])->name('proxySetting');
    Route::get('/admin/settings', [ssd\proxies\Http\Controllers\SiteSettingsController::class, 'index'])->name('allSettingSite');

    Route::post('/admin/fetch/payment/settings', [ssd\proxies\Http\Controllers\PaymentController::class, 'PaymentSettingsAdmin'])->name('PaymentSettingsAdmin');

    Route::post('/admin/fetch/setting/notice', [ssd\proxies\Http\Controllers\SiteSettingsController::class, 'noticeSettings'])->name('NoticeSettingsAdmin');
    Route::post('/admin/fetch/setting/integration', [ssd\proxies\Http\Controllers\SettingKrakenController::class, 'integrationSave'])->name('integrationSave');
    Route::post('/admin/fetch/setting/basic', [ssd\proxies\Http\Controllers\SettingKrakenController::class, 'basicSave'])->name('basicSave');
    Route::post('/admin/fetch/setting/sale', [ssd\proxies\Http\Controllers\SettingKrakenController::class, 'saleSave'])->name('saleSave');
    Route::post('/admin/fetch/balace/change', [ssd\proxies\Http\Controllers\HistoryOperationController::class, 'balanceChanges'])->name('balanceChanges');
    Route::post('/admin/fetch/balace/allsettings', [ssd\proxies\Http\Controllers\SiteSettingsController::class, 'allSettingsSiteSave'])->name('allSettingsSiteSave');
    Route::post('/admin/fetch/balace/refsettings', [ssd\proxies\Http\Controllers\SiteSettingsController::class, 'referallSettingsSiteSave'])->name('referallSettingsSiteSave');
    Route::post('/admin/fetch/setting/ceo', [ssd\proxies\Http\Controllers\SiteSettingsController::class, 'ceoSettingsSiteSave'])->name('ceoSettingsSiteSave');
    Route::post('/admin/fetch/setting/delimage', [ssd\proxies\Http\Controllers\SiteSettingsController::class, 'delimageSetting'])->name('delimageSetting');
    Route::controller(ssd\proxies\Http\Controllers\TemplateController::class)->group(function () {
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
