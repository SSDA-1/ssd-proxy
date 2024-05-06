<?php

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\User;
use Ssda1\proxies\Models\Modem;
use Ssda1\proxies\Models\Server;
use Ssda1\proxies\Models\SettingKraken;
use Ssda1\proxies\Service\ProcessLogService;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Foundation\Application;

class ModemController extends Controller
{
    /**
     * Отобразить список ресурсов.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:proxy-admin', ['only' => ['admin']]);
    }

    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }

    /**
     * Вывести форму для создания нового ресурса.
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function create(): View|Factory|RedirectResponse|Application
    {
        $users = User::all()->pluck('name', 'id')->toArray();

        $servers = Server::all();
        $firstServer = $servers->first();
        $servers = $servers->pluck('name', 'id')->toArray();

        // try {
        // $settingModel = SettingKraken::find(1);

        $apiKey = getToken($firstServer->data['url'], $firstServer->data['login'], $firstServer->data['password']);
        $freeInterfaceGet = getInterface($firstServer->data['url'], $apiKey);
        $freeInterface = [];
        foreach ($freeInterfaceGet as $key => $value) {
            $freeInterface[$value['ifname']] = $value['ifname'];
        }
        $modelModemsGet = getModems($firstServer->data['url'], $apiKey);
        $modelModems = [];
        foreach ($modelModemsGet as $key => $value) {
            $modelModems[$value['id']] = $value['name'];
        }

        if (!$apiKey) {
            $this->log(
                'Создание модема',
                "Ошибка! Не удалось получить токен",
                'Modem creation',
                "Error! Could not get token"
            );
        } elseif (empty($freeInterface)) {
            $this->log(
                'Создание модема',
                "Ошибка! Не удалось получить сетевые интерфейсы",
                'Modem creation',
                "Error! Failed to get network interfaces"
            );
        } elseif (empty($modelModems)) {
            $this->log(
                'Создание модема',
                "Ошибка! Не удалось получить модемы",
                'Modem creation',
                "Error! Could not get modems"
            );
        }

        return view('proxies::admin.modem.create', compact('users', 'freeInterface', 'modelModems', 'freeInterfaceGet', 'servers'));
        // } catch (Exception) {
        // }
        // return redirect()->route('proxy.index')
        //     ->with('success', 'Проблема с ключом');
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
            'name' => 'required',
            // 'userapi' => 'required',
            'reconnect_interval' => 'required',
        ]);

        $settingModel = SettingKraken::find(1);
        $ipSetting = $settingModel->integration_ip;
        $loginSetting = $settingModel->integration_login;
        $passwordSetting = $settingModel->integration_password;

        $namePortReq = $request->input('name');


        if ($request->input('userapi') != null) {
            $usersReq = Arr::flatten($request->input('userapi'));
        } else {
            $usersReq = [];
        }


        $interfaceReq = $request->input('interface');
        $modelReq = $request->input('model');
        $changeipReq = $request->input('changeip');
        $typePay = $request->input('typepay');
        $maxUsers = $request->input('usercount');
        $reconnect_interval = $request->input('reconnect_interval');
        $server = $request->input('server');
        $locked_ip_type_change = $request->input('locked_ip_type_change');

        $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);

        $createStorePort = creatPort($ipSetting, $namePortReq, $interfaceReq, $modelReq, true, false, null, 3, 0, "lte", $reconnect_interval, $changeipReq, '20', $apiKey, $usersReq, $typePay, $maxUsers, $server, $locked_ip_type_change);

        $this->log(
            'Добавление модема',
            "$createStorePort",
            'Modem addition',
            "$createStorePort"
        );

        return redirect()->route('proxy.index')
            ->with('success', $createStorePort); //'Пункт меню успешно создан. - '.
    }

    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
     *
     * @param int $id
     * @return View|Factory|Application|RedirectResponse
     */
    public function edit(int $id): View|Factory|Application|RedirectResponse
    {
        $port = Modem::find($id); // Выборка по ID в бд. Не по ID в кракене
        $servers = '';
        $firstServer = '';
        $servers = '';

        if (is_null($port)) {
            abort(404);
        }
        $users = User::all()->pluck('name', 'id_kraken')->toArray(); // Выборка по ID в бд. Не по ID в кракене

        // try {
        // $settingModel = SettingKraken::find(1);
        $modem = Modem::find($id);
        if ($modem->server) {
            $servers = Server::all();
            $firstServer = $modem->server;
            $servers = $servers->pluck('name', 'id')->toArray();
        } else {
            $servers = Server::all();
            $firstServer = $servers->first();
            $servers = $servers->pluck('name', 'id')->toArray();
        }


        $apiKey = getToken($firstServer->data['url'], $firstServer->data['login'], $firstServer->data['password']);
        $freeInterfaceGet = getInterface($firstServer->data['url'], $apiKey);

        $freeInterface = [];
        $freeInterface[$port->ifname] = $port->ifname;

        foreach ($freeInterfaceGet as $key => $value) {
            $freeInterface[$value['ifname']] = $value['ifname'];
        }
        $modelModemsGet = getModems($firstServer->data['url'], $apiKey);

        $modelModems = [];

        foreach ($modelModemsGet as $key => $value) {
            $modelModems[$value['id']] = $value['name'];
        }

        if (!$apiKey) {
            $this->log(
                'Редактирование модема',
                "Ошибка! Не удалось получить токен",
                'Modem editing',
                "Error! Could not get token"
            );
        } elseif (empty($freeInterface)) {
            $this->log(
                'Редактирование модема',
                "Ошибка! Не удалось получить сетевые интерфейсы",
                'Modem editing',
                "Error! Failed to get network interfaces"
            );
        } elseif (empty($modelModems)) {
            $this->log(
                'Редактирование модема',
                "Ошибка! Не удалось получить модемы",
                'Modem editing',
                "Error! Could not get modems"
            );
        }

        return view('proxies::admin.modem.edit', compact('port', 'users', 'freeInterface', 'modelModems', 'servers'));
        // } catch (Exception) {
        // }
        // return redirect()->route('proxy.index')
        //     ->with('success', 'Проблема с ключом');
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
            'name' => 'required',
        ]);

        // try {
        $modem = Modem::find($id);
        $iDKraken = $modem->id_kraken;
        $modemServerDef = !isset($modem->server) ?: '';
        $settingModel = SettingKraken::find(1);
        $ipSetting = $settingModel->integration_ip;
        $loginSetting = $settingModel->integration_login;
        $passwordSetting = $settingModel->integration_password;
        $usersReq = null;
        // $idKraken = Modem::find($id)->id_kraken;
        $namePortReq = $request->input('name');
        if ($request->input('userapi') !== null) {
            $usersReq = Arr::flatten($request->input('userapi'));
        } else {
            $usersReq = $modem->users;
        }

        $interfaceReq = $request->input('interface');
        $modelReq = $request->input('model');
        $changeipReq = $request->input('changeip');
        $typePay = $request->input('typepay');
        $maxUsers = $request->input('usercount');
        $reconnect_interval = $request->input('reconnect_interval');
        $server = $request->input('server');
        $locked_ip_type_change = $request->input('locked_ip_type_change');

        if (isset($modemServerDef) and isset($modemServerDef->id)  ==  $server) {
            $settingModel = $modem->server;
            $ipSetting = $settingModel->data['url'];
            $loginSetting = $settingModel->data['login'];
            $passwordSetting = $settingModel->data['password'];
            $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);

            $editStorePort = editPort($iDKraken, $ipSetting, $namePortReq, $interfaceReq, $modelReq, true, false, null, 3, 0, "lte", $reconnect_interval, $changeipReq, '20', $apiKey, $usersReq, $typePay, $maxUsers, $server, $locked_ip_type_change);
        } else {
            $settingModel = Server::find($server);
            $ipSetting = $settingModel->data['url'];
            $loginSetting = $settingModel->data['login'];
            $passwordSetting = $settingModel->data['password'];
            $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);

            $editStorePort = editPort($iDKraken, $ipSetting, $namePortReq, $interfaceReq, $modelReq, true, false, null, 3, 0, "lte", $reconnect_interval, $changeipReq, '20', $apiKey, $usersReq, $typePay, $maxUsers, $server, $locked_ip_type_change);
        }

        $this->log(
            'Редактирование модема',
            "$editStorePort",
            'Modem editing',
            "$editStorePort"
        );

        return redirect()->route('proxy.index')
            ->with('success', $editStorePort);
        // } catch (Exception) {
        // }
        // return redirect()->route('proxy.index')
        //     ->with('success', 'Что-то пошло не так.');
    }

    /**
     * Получить количество портов
     * @return int
     */
    public function getCountModems(): int
    {
        return Modem::all()->count();
    }

    /**
     * Получить количество занятых портов (возможное количество человек заполнено)
     *
     * @return int
     */
    public function getCountModemsByFilledUsers(): int
    {
        $servers = Server::all();
        $countModemsFull = 0;

        foreach ($servers as $server) {
            $countModemsFull += $server->modems
                ->where('proxyfull', '==', 'full')
                ->count();
        }

        return $countModemsFull;

        /*return Modem::join('proxies', 'modems.id', '=', 'proxies.modem_id')
        ->select('modems.*')
        ->groupBy('modems.id')
        ->havingRaw('COUNT(DISTINCT proxies.id) >= modems.max_users')
        ->count();*/
    }


    /**
     * Удалить указанный ресурс из хранилища
     *
     * @param  int  $id
     * @return array
     */
    public function destroy($id)
    {
        $modem = Modem::find($id);
        $modemProxys = $modem->proxys;
        foreach ($modemProxys as $key => $proxy) {
            $proxy->delete();
        }
        $id = $modem->id;

        try {
            $modem->delete();
        } catch (\Exception $exception) {
            $this->log(
                'Удаление модема',
                "Ошибка! Модем $id не удален",
                'Demodem',
                "Error! Modem $id not deleted"
            );
        }

        $this->log(
            'Удаление модема',
            "Успешно! Модем $id удален",
            'Demodem',
            "Successful! Modem $id deleted"
        );

        $returnArray['status'] = true;
        $returnArray['action'] = 'delTable';
        $returnArray['tr'] = 'modem_' . $id;
        $returnArray['massage'] = 'Модем №' . $id . ' успешно удалён';
        return $returnArray;

        // return redirect()->route('proxy.index');
    }
}
