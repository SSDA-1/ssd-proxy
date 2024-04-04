<?php

namespace ssd\proxies\Service;

use ssd\proxies\Models\User;
use ssd\proxies\Models\Modem;
use ssd\proxies\Models\Proxy;
use ssd\proxies\Models\Server;
use ssd\proxies\Models\SettingKraken;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProxyGlobalService
{
    public function changeUser($proxy, $login, $password, $password1)
    {
        $user = Auth::user();
        $server = $proxy->modem->server;
        $apiKey = getToken($server->data['url'], $server->data['login'], $server->data['password']);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token ' . $apiKey
        ])->post($server->data['url'] . '/api/proxy/auth/edit/' . $proxy->id_user_proxy_kraken, [
            'login' => $login,
            'password' => $password,
            'bitrate_in' => 0,
            'bitrate_out' => 0
        ]);

        // $url = $server->data['url'].'/api/proxy/auth/edit/'.$proxy->id_user_proxy_kraken;
        // $data = array(
        //     'login' => $login,
        //     'password' => $password,
        //     'bitrate_in' => 0,
        //     'bitrate_out' => 0
        // );
        // $headers = array(
        //     'Content-Type: application/json',
        //     'Authorization: Token '.$apiKey
        // );

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $response = curl_exec($ch);
        // curl_close($ch);

        // $responseData = $response->json();

        if ($response->successful()) { //and $modemData['active'] == true
            // if(curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200) {
            // $proxy->login_user_proxy_kraken = $login;
            // $proxy->password_user_proxy_kraken = $password;
            // $proxy->save();
            $content = json_encode($response->json());
            file_put_contents('example.txt', $content);
            $proxies = Proxy::where('id_user_proxy_kraken', $proxy->id_user_proxy_kraken)->get();
            $response_data = $response->json();

            if (isset($response_data['errors']['login'])) {
                $error_message = $response_data['errors']['login'][0];
                return 'Такое имя пользователя занято - пожалуйста укажите другое.'; // Прокси работает

            } else {
                foreach ($proxies as $prox) {
                    $prox->login_user_proxy_kraken = $login;
                    $prox->password_user_proxy_kraken = $password;
                    $prox->save();
                }
                $user = User::find(Auth::user()->id);
                $user->kraken_password = $password;

                $user->save();
            }
        } else {
            return 'Пользователь изменён'; // Прокси не работает
        }
    }

    public function getUserProxy($proxy)
    {
        $user = Auth::user();
        $server = $proxy->modem->server;
        $apiKey = getToken($server->data['url'], $server->data['login'], $server->data['password']);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token ' . $apiKey
        ])->post($server->data['url'] . '/api/proxy/auth/edit/' . $proxy->id_user_proxy_kraken, [
            'login' => $login,
            'password' => $password,
            'bitrate_in' => 0,
            'bitrate_out' => 0
        ]);

        $responseData = $response->json();

        if ($response->successful()) { //and $modemData['active'] == true
            return true; // Прокси работает
        } else {
            return false; // Прокси не работает
        }
    }
    public function autopay($proxy, $proxy2, $days, $price = null)
    {
        $autopay = $proxy->autopay;
        $return = '';

        if ($autopay == 1) {
            $proxy->autopay = 0;
            $proxy2->autopay = 0;
            $return = 'Выключено';
        } else {
            $proxy->autopay = 1;
            $proxy2->autopay = 1;
            $proxy->autopay_days = $days;
            $proxy2->autopay_days = $days;

            if (!empty($price)) {
                $proxy->price = $price;
                $proxy2->price = $price;
            }
            $return = 'Включено';
        }
        $proxy->save();
        $proxy2->save();
        return $return;
    }


    public function restart($modem)
    {
        $server = $modem->server;
        $apiKey = getToken($server->data['url'], $server->data['login'], $server->data['password']);

        // First, turn off the device
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token ' . $apiKey,
        ])->post($server->data['url'] . '/api/devices/modem/active', [
            'id' => [$modem->id_kraken],
            'active' => false,
        ]);

        // Then, wait for a second to make sure the device is turned off
        sleep(1);

        // Finally, turn on the device
        $response2 = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token ' . $apiKey,
        ])->post($server->data['url'] . '/api/devices/modem/active', [
            'id' => [$modem->id_kraken],
            'active' => true,
        ]);

        // Декодируем JSON-ответ
        $jsonResponse = json_decode($response->body(), true);

        // Проверяем значение ключа "result"
        if (isset($jsonResponse['result']) and $jsonResponse['result'] === 'ok') {
            return true;
            // Можно выполнить дополнительные действия здесь
        } else {
            // Значение ключа "result" не равно "ok"
            return false;
        }
    }

    public function changeIP($proxy, $proxy2)
    {
        $proxy = Proxy::find($proxy);
        if ($proxy) {
            $server = $proxy->modem->server;
            $apiKey = getToken($server->data['url'], $server->data['login'], $server->data['password']);

            // First, turn off the device
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Token ' . $apiKey,
            ])->post($server->data['url'] . '/api/devices/modem/reconnect', [
                'id' => [$proxy->modem->id_kraken],
            ]);

            // Декодируем JSON-ответ
            $jsonResponse = json_decode($response->body(), true);

            // Проверяем значение ключа "result"
            if (isset($jsonResponse['result'])) {
                return true;
                // Можно выполнить дополнительные действия здесь
            } else {
                // Значение ключа "result" не равно "ok"
                return false;
            }
        } else {
            // Значение ключа "result" не равно "ok"
            return false;
        }
    }
}
