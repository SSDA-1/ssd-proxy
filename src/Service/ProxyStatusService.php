<?php

namespace ssd\proxies\Service;

use ssd\proxies\Models\Modem;
use ssd\proxies\Models\SettingKraken;
use ssd\proxies\Models\Server;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProxyStatusService
{
    public function check($modemID, $type, $login)
    {
        $user = Auth::user();
        $modem = Modem::find($modemID);
        $server = $modem->server;
        $apiKey = getToken($server->data['url'], $server->data['login'], $server->data['password']);

        if ($apiKey) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Token ' . $apiKey
            ])->post($server->data['url'] . '/api/proxy/list', [
                'filter' => [
                    'modem__id' => $modem->id_kraken,
                    'auth_login__login__icontains' => '', //$user->kraken_username,
                    'type' => $type
                ]
            ]);

            $responseSearch = collect($response->json())->where('auth_params.login', '');
            $modemData = $response->json();

            if ($response->successful() and isset($modemData['data'][0]['active']) == true) { //and $modemData['active'] == true
                return true; // Прокси работает
            } else {
                return false; // Прокси не работает
            }
        } else {
            return false; // Сервер не отвечает
        }
    }
}
