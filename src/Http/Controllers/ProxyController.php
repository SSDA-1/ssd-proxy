<?php

namespace ssd\proxies\Http\Controllers;

use ssd\proxies\Models\User;
use ssd\proxies\Models\Modem;
use ssd\proxies\Models\Proxy;
use ssd\proxies\Models\Server;
use ssd\proxies\Models\Promocode;
use ssd\proxies\Models\ProcessLog;
use ssd\proxies\Models\CountPairsProxyDiscount;
use ssd\proxies\Models\SettingKraken;
use ssd\proxies\Models\TariffSettings;
use ssd\proxies\Models\CountDaysDiscount;
use ssd\proxies\Models\CountProxyDiscount;
use ssd\proxies\Service\ProcessLogService;
use ssd\proxies\Service\ProxyGlobalService;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Foundation\Application;
use Carbon\Carbon;

class ProxyController extends Controller
{

    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }
    /**
     * Отобразить список ресурсов.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:proxy-admin', ['only' => ['admin']]);
    }

    /**
     * Отобразить форму для создания нового ресурса.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $ports = Modem::all();
        $proxys = Proxy::all();
        $settingModel = SettingKraken::find(1);
        $servers = json_encode(Server::all()->pluck('id', 'name')->toArray());
        $serversId = Server::all();
        return view('proxies::admin.proxy', compact('ports', 'proxys', 'settingModel', 'servers', 'serversId'));
    }

    public function searchPorts(Request $request)
    {
        $query = $request->input('query');

        $servers = Server::all();
        $ports = Modem::all();
        $data = [];

        $userIds = User::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->orWhere('telegram_name', 'like', "%$query%")
            ->pluck('id');

        $proxyIds = Proxy::whereIn('user_id', $userIds)->pluck('id');
        foreach ($ports as $port) {
            $proxys = $port->proxys()->whereIn('id', $proxyIds)->get();
            if (!$proxys->isEmpty()) {
                foreach ($proxys as $proxy)
                    $proxy->setAttribute('user', $proxy->user);
                $port->setAttribute('server', $port->server->id);
                $port->setAttribute('proxys', empty($proxys) ? [] : $proxys);
                $data = array_merge($data, [$port]);
            }
        }

        return response()->json(['ports' => $data, 'servers' => $servers]);
    }

    /**
     * Отобразить форму для создания нового ресурса.
     *
     * @return Application|Factory|View
     */
    public function setting(): View|Factory|Application
    {
        $settingModel = SettingKraken::find(1);
        $servers = Server::all();
        $promocodes = Promocode::all();
        $countProxyDiscounts = CountProxyDiscount::all();
        $countDaysDiscounts = CountDaysDiscount::all();
        $tariffSetting = TariffSettings::find(1);
        $countPairsProxyDiscounts = CountPairsProxyDiscount::all();
        $tariffCountries = Server::distinct()->pluck('country')->toArray();

        return view('proxies::admin.proxy.setting', [
            'settingModel' => $settingModel,
            'servers' => $servers,
            'promocodes' => $promocodes,
            'countProxyDiscounts' => $countProxyDiscounts,
            'countDaysDiscounts' => $countDaysDiscounts,
            'tariffSetting' => $tariffSetting,
            'countPairsProxyDiscounts' => $countPairsProxyDiscounts,
            'tariffCountries' => $tariffCountries
        ]);
    }

    /**
     * Вывести форму для создания нового ресурса.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        $portsModel = Modem::all();
        $ports = $portsModel->filter(function ($model) {
            $httporsocs = $model->proxycount * 2;
            return $model->proxyhttporsocs != $httporsocs or $model->proxycount == 0;
        })->pluck('name', 'id')->toArray();

        $users = User::all()->pluck('name', 'id_kraken')->toArray();
        return view('proxies::admin.proxy.create', compact('ports', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        request()->validate([
            'port' => 'required',
            'number' => 'required'
        ]);

        // try {

        $end_date = $request->input('end_date');
        $end_time = $request->input('end_time');

        $portReq = $request->input('port');
        $modem = Modem::find($portReq);
        $portKraken = $modem->id_kraken;

        $settingModel = $modem->server; //SettingKraken::find(1);
        $ipSetting = $settingModel->data['url'];
        $loginSetting = $settingModel->data['login'];
        $passwordSetting = $settingModel->data['password'];

        $typeReq = $request->input('type');
        $numberReq = $request->input('number');
        $dateEndProxys = $end_date . ' ' . $end_time;
        $maxconnectReq = $request->input('maxconnect');
        // $userId = $request->input('user');
        $userReq = $request->input('user');
        $userId = User::where('id_kraken', '=', $userReq)->value('id');
        $auth = 'strong';

        $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);

        $createStoreProxy = createProxy($ipSetting, $portKraken, true, $typeReq, $auth, false, $maxconnectReq, $numberReq, $apiKey, $userReq, $userId, $portReq, $dateEndProxys);

        $this->log(
            'Добавление прокси',
            "$createStoreProxy",
            'Proxy addition',
            "$createStoreProxy"
        );

        // return redirect()->route('proxy.index')
        //     ->with('success', $createStoreProxy); //'Пункт меню успешно создан. - '.
        // } catch (\Exception) {

        // }
        // return redirect()->route('proxy.index')
        //     ->with('success', 'Проблема с ключом');

        return redirect()->route('proxy.index')
            ->with('success', 'Успешно');
    }

    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id): Application|Factory|View
    {
        $proxy = Proxy::find($id);

        if (is_null($proxy)) {
            abort(404);
        }

        $where = array();
        $dataReverseType = $proxy->type == 'http' ? 'socs' : 'http';
        $where[] = ['type', '=', $dataReverseType];
        $where[] = ['user_id', '=', $proxy->user_id];
        $proxyReverse = Proxy::where($where)->get();
        if ($proxyReverse->isEmpty()) {
            $httpOrsocsArray = ['http' => 'http', 'socks' => 'socks'];
        } else {
            if ($dataReverseType == 'socs') {
                $httpOrsocsArray = ['http' => 'http'];
            } else {
                $httpOrsocsArray = ['socks' => 'socks'];
            }
        }

        $portsModel = Modem::all();
        $ports = $portsModel->filter(function ($model) {
            $httporsocs = $model->proxycount * 2;
            // if($model->proxycount != 0){
            return $model->proxyhttporsocs != $httporsocs or $model->proxycount == 0;
            // }
        })->pluck('id', 'id')->toArray();

        $users = User::all()->pluck('name', 'id')->toArray();

        $settingModel = SettingKraken::find(1);

        return view('proxies::admin.proxy.edit', compact('proxy', 'ports', 'users', 'settingModel', 'httpOrsocsArray'));
    }

    /**
     * Обновить указанный ресурс в хранилище.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->validate($request, [
            'number' => 'required',
        ]);

        $proxy = Proxy::find($id);
        $settingModel = SettingKraken::find(1);

        // $servers = Server::all();
        $firstServer = $proxy->modem->server;

        $ipSetting = $firstServer->data['url'];
        $loginSetting = $firstServer->data['login'];
        $passwordSetting = $firstServer->data['password'];

        $end_date = $request->input('end_date');
        $end_time = $request->input('end_time');

        $portReq = $request->input('port');
        $idKrakenReq = $request->input('id_kraken');
        $portKraken = Modem::find($portReq)->id_kraken;
        $typeReq = $request->input('type');
        $numberReq = $request->input('number');
        $maxconnectReq = $request->input('maxconnect');
        $userId = $request->input('user');
        $userReq = $request->input('user');
        $dateEndProxys = $end_date . ' ' . $end_time;
        $auth = 'strong';
        // Смена Логина и пароля прокси
        $userProxyLoginDef = $proxy->login_user_proxy_kraken ?: $user->kraken_username;
        $userProxyPasswordDef = $proxy->password_user_proxy_kraken ?: $user->kraken_username;
        $login_user_proxy = $request->input('login_user_proxy');
        $password_user_proxy = $request->input('password_user_proxy');

        if ($userProxyLoginDef != $login_user_proxy and $userProxyPasswordDef != $password_user_proxy) {
            $proxyGlobal = new ProxyGlobalService();
            $export = $proxyGlobal->changeUser($proxy, $login_user_proxy, $password_user_proxy, $password_user_proxy);
            // $export = $proxyGlobal->changeUser($proxy2,$login_user_proxy,$password_user_proxy,$password_user_proxy);
        }
        //

        $namePortReq = $request->input('name');

        $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);

        // $editStorePort = editPort($id, $ipSetting, $namePortReq, $interfaceReq, $modelReq, true, false, null, 3, 0, "lte", '2592000', $changeipReq, '20', $apiKey, $usersReq, $typePay, $maxUsers);
        $editStoreProxy = editProxy($ipSetting, true, $typeReq, $auth, false, $maxconnectReq, $numberReq, $apiKey, $userReq, $userId, $portReq, $id, $idKrakenReq, $dateEndProxys);

        $this->log(
            'Редактирование прокси',
            "$editStoreProxy",
            'Proxy editing',
            "$editStoreProxy"
        );

        // return redirect()->route('proxy.index')
        //     ->with('success', $editStoreProxy);
        return redirect()->route('proxy.index')
            ->with('success', 'Успешно');
    }

    public function getProxies(): int
    {
        $now = Carbon::now();
        return Proxy::all()->count();
    }
    public function getProxiesPause(): int
    {
        return Proxy::where('active', 0)->count();
    }

    /**
     * Удалить указанный ресурс из хранилища
     *
     * @param int $id
     * @return array|RedirectResponse
     */
    public function destroy(int $id): array|RedirectResponse
    {
        // User::find($id)->delete();
        $proxy = Proxy::find($id);

        if (is_null($proxy)) {
            abort(404);
        }

        $settingModel = $proxy->modem->server; //SettingKraken::find(1);
        $ipSetting = $settingModel->data['url'];
        $loginSetting = $settingModel->data['login'];
        $passwordSetting = $settingModel->data['password'];

        $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);

        try {
            deliteProxy($ipSetting, $proxy->id_kraken, $apiKey, $proxy->id);

            $this->log(
                'Удаление прокси',
                "Успешно! Прокси $id удален",
                'Removing a proxy',
                "Successful! Proxy $id removed"
            );
        } catch (\Exception $exception) {
            $this->log(
                'Удаление прокси',
                "Ошибка! Прокси $id не удален",
                'Removing a proxy',
                "Error! Proxy $id not deleted"
            );
        }

        $returnArray['status'] = true;
        $returnArray['action'] = 'delTable';
        $returnArray['tr'] = 'proxy_' . $id;
        $returnArray['massage'] = 'Прокси №' . $id . ' успешно удалён';
        return $returnArray;
    }
}
