<?php

namespace ssd\proxies\Service;

use ssd\proxies\Models\Modem;
use ssd\proxies\Models\SettingKraken;
use ssd\proxies\Models\Server;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ServerStatusService
{
    public function check($server)
    {
        $user = Auth::user();
        // $server = $modem->server;
        $apiKey = getToken($server->data['url'], $server->data['login'], $server->data['password']);

        if ($apiKey) { 
            return true; // Прокси работает
        } else {
            return false; // Прокси не работает
        }
    }
}