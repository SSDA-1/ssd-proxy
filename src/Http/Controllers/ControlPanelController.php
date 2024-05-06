<?php

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\Server;
use Ssda1\proxies\Models\SettingKraken;
use Ssda1\proxies\Service\ProcessLogService;
use Ssda1\proxies\Service\ProxyStatusService;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Foundation\Application;


class ControlPanelController extends Controller
{
    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {

        $input = $request->all();

        $user = Auth::user();
        $proxis = isset($user->proxys) ? $user->proxys : null;
        $dataProxy = [];
        $i = 0;
        if ($proxis !== null) {
            foreach ($proxis as $proxy) {
                $dataProxy[$i++] = $proxy;
            }
        }

        $servers = Server::all();
        $firstServer = $servers->first();
        $serversCountry = $servers->pluck('country')->toArray();

        $settingModel = SettingKraken::find(1);
        if ($settingModel == null || $settingModel->integration_login == null) {
            return view('proxies::admin.lk.control-panel', [
                'proxis' => $proxis,
                'dataProxy' => $dataProxy,
                'settingModel' => $settingModel,
                'ifname' => null,
                'error' => null,
                'serversCountry' => $serversCountry
            ]);
        } else {
            try {
                $usernameIntegration = $settingModel->integration_login;
                $passwordIntegration = $settingModel->integration_password;
                $tokenUser = getToken($firstServer->data['url'], $firstServer->data['login'], $firstServer->data['password']);
                $tokenAdmin = getToken($firstServer->data['url'], $firstServer->data['login'], $firstServer->data['password']);
                if ($tokenUser) {
                    $ifname = getIfname($firstServer->data['url'], $tokenUser);
                } else {
                    $ifname = null;
                }
                return view('proxies::admin.lk.control-panel', [
                    'proxis' => $proxis,
                    'dataProxy' => $dataProxy,
                    'settingModel' => $settingModel,
                    'tokenUser' => $tokenUser,
                    'ifname' => $ifname,
                    'error' => null,
                    'serversCountry' => $serversCountry
                ]);
            } catch (Exception $e) {
                $message = $e->getMessage();
                $this->log(
                    'Личный кабинет',
                    "Ошибка! С выводом панели в личном кабинете ($message)",
                    'Personal account',
                    "Error! With the panel in your account ($message)"
                );

                return view('proxies::admin.lk.control-panel', [
                    'proxis' => $proxis,
                    'dataProxy' => $dataProxy,
                    'settingModel' => $settingModel,
                    'ifname' => null,
                    'error' => $e->getMessage()
                ]);
            }
        }


        // $advantags = Advantag::paginate(10);
        // return view('proxies::admin.advantags.index', compact('advantags'))
        //     ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function statusProxy(Request $request) {

        $modemID = $request->input('modem');
        $type = $request->input('type');
        $loginKraken = $request->input('login');

        $proxyStatusService = new ProxyStatusService;
        $status = $proxyStatusService->check($modemID, $type, $loginKraken);

        return $status;
    }
}
