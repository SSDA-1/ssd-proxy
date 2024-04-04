<?php

use ssd\proxies\Models\User;
use ssd\proxies\Models\Modem;
use ssd\proxies\Models\Proxy;
use ssd\proxies\Models\SettingNotices;
use ssd\proxies\Models\SettingKraken;
use ssd\proxies\Models\HistoryOperation;
use ssd\proxies\Models\Server;
use ssd\proxies\Service\KrakenService;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

// Получение токена
if (!function_exists('getToken')) {
    function getToken($ip, $username, $password)
    {
        $data = [
            'ip' => $ip,
            'data' => [
              'username' => $username,
              'password' => $password
            ]
        ];

        $krakenService = new KrakenService();
        $result = $krakenService->getAuthLogin($data);
        $result = json_decode($result, JSON_UNESCAPED_UNICODE);

        return $result['key'] ?? false;
    }
}
// Получение токена

// Создание Порта
if (!function_exists('creatPort')) {
    function creatPort(
        $ip,
        $namePort = false,
        $ifname,
        $type,
        $active = true,
        $isOsfp = false,
        $osfp = null,
        $monitoringInterval = 3,
        $mtu = 0,
        $netMode = "lte",
        $reconnect_interval,
        $reconnectType = 'time_link',
        $reconnectMin = '20',
        $apiKey,
        $users,
        $typePay = 'private',
        $maxUsers = '1',
        $server,
        $locked_ip_type_change
    ) {
        if (!$namePort) {
            $numberDef = 37;
            $numberNext = $numberDef + 1;
            $namePort = 'Порт(sN)-' . $numberNext;
        }

        $data = [
          'ip' => $ip,
          'data' => [
              'name' => $namePort,
              'ifname' => $ifname,
              'type' => $type,
              'active' => $active,
              'is_osfp' => $isOsfp,
              'osfp' => $osfp,
              'monitoring_interval' => $monitoringInterval,
              'mtu' => $mtu,
              'net_mode' => $netMode,
              'reconnect_interval' => $reconnect_interval,
              'reconnect_type' => $reconnectType,
              'reconnect_min' => $reconnectMin,
              'reconnect_event' => ['1'],
              'users' => $users,
              'params' => null
          ],
          'api_key' => $apiKey
        ];

        $krakenService = new KrakenService();
        $result = $krakenService->getDevicesModemAdd($data);
        $responseCreateApi = json_decode($result, JSON_UNESCAPED_UNICODE);

        $hasErrors = Arr::has($responseCreateApi, 'error');
        if ($hasErrors) {
            $return = 'Ошибка создания';
        } else if (!in_array('id', $responseCreateApi)) {
            $return = json_encode($responseCreateApi['errors']);
        } else {
            $return = 'Порт создан';
            $newModem = new Modem;
            $newModem->name = $namePort;
            $newModem->ifname = $ifname;
            $newModem->type = $type;
            $newModem->active = $active;
            $newModem->net_mode = $netMode;
            $newModem->is_osfp = $isOsfp;
            $newModem->osfp = $osfp;
            $newModem->reconnect_type = $reconnectType;
            $newModem->reconnect_interval = $reconnect_interval;
            $newModem->reconnect_min = $reconnectMin;
            $newModem->users = $users;
            $newModem->type_pay = $typePay;
            $newModem->max_users = $maxUsers;
            $newModem->id_kraken = $responseCreateApi['id'];
            $newModem->server_id = $server;
            $newModem->locked_ip_type_change = $locked_ip_type_change;
            $newModem->save();
        }

        return $return;
    }
}
// Создание Порта

// Редактирование Порта
if (!function_exists('editPort')) {
    function editPort($id, $ip, $namePort = false, $ifname, $type, $active = true, $isOsfp = false, $osfp = null, $monitoringInterval = 3, $mtu = 0, $netMode = "lte", $reconnect_interval, $reconnectType = 'time_link', $reconnectMin = '20', $apiKey, $users, $typePay = 'private', $maxUsers = '1', $server, $locked_ip_type_change)
    {
        if ($namePort == false) {
            $numberDef = 37;
            $numberNext = $numberDef + 1;
            $namePort = 'Порт(sN)-' . $numberNext;
        }
        if ($osfp == 'none') {
            $isOsfp = false;
            $osfp = 0;
        }
        $newModem = Modem::where('id_kraken', $id)->where('server_id', $server)->first();

        $data = [
            'ip' => $ip,
            'data' => [
                'name' => $namePort,
                'ifname' => $ifname,
                'type' => $type,
                'active' => $active,
                'is_osfp' => $isOsfp,
                'osfp' => $osfp,
                'monitoring_interval' => $monitoringInterval,
                'mtu' => $mtu,
                'net_mode' => $netMode,
                'reconnect_interval' => $reconnect_interval,
                'reconnect_type' => ($locked_ip_type_change == 2 ? $newModem->reconnect_type : $reconnectType),
                'reconnect_min' => $reconnectMin,
                'reconnect_event' => ['1'],
                'users' => $users,
                'params' => null
            ],
            'api_key' => $apiKey,
            'id_kraken' => $newModem->id_kraken,
        ];

        $krakenService = new KrakenService();
        $result = $krakenService->getDevicesModemEdit($data);
        $responseCreateApi = json_decode($result, JSON_UNESCAPED_UNICODE);

        $hasErrors = Arr::has($responseCreateApi, 'error');
        if ($hasErrors) {
            $return = 'Ошибка создания';
        } else {
            $return = 'Порт отредактирован' . json_encode($responseCreateApi) . '---' . json_encode($users); //- '.$responseCreateApi['errors']['params'][0]

            $newModem->name = $namePort;
            $newModem->ifname = $ifname;
            $newModem->type = $type;
            $newModem->active = $active;
            $newModem->net_mode = $netMode;
            $newModem->is_osfp = $isOsfp != false ? $isOsfp : null;
            $newModem->osfp = $osfp != 0 ? $osfp : null;
            $newModem->reconnect_interval = $reconnect_interval;
            $newModem->reconnect_min = $reconnectMin;
            $newModem->users = $users;
            $newModem->type_pay = $typePay;
            $newModem->max_users = $maxUsers;
            $newModem->id_kraken = $id;
            $newModem->server_id = $server;
            if ($locked_ip_type_change < 2) {
                $newModem->reconnect_type = $reconnectType;
                $newModem->locked_ip_type_change = $locked_ip_type_change;
            }
            $newModem->reconnect_type_fake = $reconnectType;

            $newModem->save();
        }
        return $return;
    }
}
// Редактирование Порта

// Редактирование Прокси
if (!function_exists('editProxy')) {
    function editProxy($ip, $active = true, $type, $auth = 'strong', $isLocal = false, $maxconn = 0, $port, $apiKey, $authParams, $user, $modemId, $idRow, $idKraken, $dateEndProxys)
    {
        // Генерируем пользователя
        $userLogin = User::find($authParams)->kraken_username;
        $hasUser = 0;

        $dataProxyAuthList = [
            'ip' => $ip,
            'api_key' => $apiKey
        ];

        $krakenService = new KrakenService();
        $resultProxyAuthList = $krakenService->getProxyAuthList($dataProxyAuthList);
        $setIntRes = json_decode($resultProxyAuthList, JSON_UNESCAPED_UNICODE);

        foreach ($setIntRes as $val) {
            if ($val['login'] == $userLogin) {
                $hasUser = $val['id'];
            }
        }

        if ($hasUser != 0) {
            $newUserProxy = $hasUser;
        } else {
            $dataProxyAuthAdd = [
                'ip' => $ip,
                'data' => [
                    'login' => $userLogin,
                    'password' => $userLogin,
                    'bitrate_in' => 0,
                    'bitrate_out' => 0,
                ],
                'api_key' => $apiKey
            ];

            $krakenService = new KrakenService();
            $resultProxyAuthAdd = $krakenService->getProxyAuthAdd($dataProxyAuthAdd);
            $responseCreateApiUserProxy = json_decode($resultProxyAuthAdd, JSON_UNESCAPED_UNICODE);

            $newUserProxy = $responseCreateApiUserProxy['id'];
        }
        // Конец генерации пользователя

        $dataProxyEdit = [
            'ip' => $ip,
            'data' => [
                'modem' => $modemId,
                'active' => $active,
                'type' => $type,
                'auth' => $auth,
                'port' => $port,
                'is_local' => $isLocal,
                'maxconn' => $maxconn,
                'is_allow' => true,
                'is_ports' => true,
                'auth_params' => [$newUserProxy],
                "allow" => [],
                "targetport" => []
            ],
            'api_key' => $apiKey,
            'id_kraken' => $idKraken,
        ];

        $krakenService = new KrakenService();
        $resultProxyEdit = $krakenService->getProxyEdit($dataProxyEdit);
        $responseCreateApi = json_decode($resultProxyEdit, JSON_UNESCAPED_UNICODE);

        $hasErrors = Arr::has($responseCreateApi, 'error');
        if ($hasErrors) {
            $return = 'Ошибка Редактирования';
        } else {
            $return = 'Прокси отредактирован';
            $proxy = Proxy::find($idRow);
            $proxy->type = $type;
            $proxy->auth = $auth;
            $proxy->number_proxy = $port;
            $proxy->ifname = null;
            $proxy->user_id = $user;
            $proxy->modem_id = $modemId;
            $proxy->date_end = !isset($dateEndProxys) ? $proxy->date_end : $dateEndProxys;
            $proxy->id_user_proxy_kraken = $newUserProxy;
            $proxy->save();
        }
        return $return;
    }
}
// Редактирование Прокси

// Обновление пользователя API в Порт
if (!function_exists('editPortUsersApi')) {
    function editPortUsersApi($id, $user, $apiKey, $ip)
    {
        $modem = Modem::where('id_kraken', $id)->first();
        $json_array = $modem->users;
        array_push($json_array, $user);
        $json_array = array_filter($json_array, function ($value) {
            return $value !== null;
        });
        $json_array = array_unique($json_array);
        $json_array = array_values($json_array);
        $is_osfp = $modem->is_osfp ?? false;

        $data = [
            'ip' => $ip,
            'data' => [
                'name' => $modem->name,
                'ifname' => $modem->ifname,
                'type' => $modem->type,
                'active' => $modem->active,
                'is_osfp' => $is_osfp,
                'osfp' => $modem->osfp,
                'monitoring_interval' => 3,
                'mtu' => 0,
                'net_mode' => $modem->net_mode,
                'reconnect_interval' => $modem->reconnect_interval,
                'reconnect_type' => $modem->reconnect_type,
                'reconnect_min' => $modem->reconnect_min,
                'reconnect_event' => ['1'],
                'users' => $json_array,
                'params' => null
            ],
            'api_key' => $apiKey,
            'id_kraken' => $id,
        ];

        $krakenService = new KrakenService();
        $result = $krakenService->getDevicesModemEdit($data);
        $responseCreateApi = json_decode($result, JSON_UNESCAPED_UNICODE);

        return $result . '--' . $is_osfp . '+' . $modem->osfp . '-+-' . json_encode($json_array);
    }
}
// Обновление пользователя API в Порт

// Блокирование Прокси
if (!function_exists('blockProxy')) {
    function blockProxy($ip, $active = true, $type, $auth = 'strong', $isLocal = false, $maxconn = 0, $port, $apiKey, $authParams, $user, $modemId, $idRow, $idKraken, $dateEndProxys)
    {
        $data = [
            'ip' => $ip,
            'data' => [
                'id' => [$idKraken],
                'active' => $active,
            ],
            'api_key' => $apiKey
        ];

        $krakenService = new KrakenService();
        $result = $krakenService->getProxyActive($data);
        $responseCreateApi = json_decode($result, JSON_UNESCAPED_UNICODE);

        $hasErrors = Arr::has($responseCreateApi, 'error');
        if ($hasErrors) {
            $return = 'Ошибка Редактирования';
        } else {
            $return = 'Прокси отредактирован';
            $proxy = Proxy::find($idRow);
            $proxy->active = $active;
            $proxy->save();
            $user = $proxy->user;

            if (!$active) {
                $settingNotices = SettingNotices::find(1);
                $users = User::all();
                $admins = $users->filter(function ($admin) {
                    return $admin->hasRole('Admin');
                });

                foreach ($admins as $admin) {
                    $chatId = $admin->telegram_chat_id;
                    if ($chatId and $settingNotices->telegram_check == 1) {
                        $telegram = new Api($settingNotices->telegram_token);

                        $email = $user->email;
                        $tg = $user->telegram_name;
                        if ($tg)
                            $text1 = "$email / $tg";
                        else
                            $text1 = "$email";

                        $portName = $proxy->modem->name;

                        $telegram->sendMessage([
                            'chat_id' => $chatId,
                            'text' => "Прокси заблокирован!\nПорты:$portName\nПользователь: $text1\n"
                        ]);
                    }
                }

                $subject = "Прокси заблокирован!";
                $to = $user->email;

                Mail::send('emails.proxy_block', ['proxy' => $proxy->id, 'proxy2' => $proxy->id + 1, 'user' => $user], function ($message) use ($subject, $to) {
                    $message->subject($subject);
                    $message->to($to);
                });
            }
        }
        $errorLogFile = 'error_log.txt';
        file_put_contents($errorLogFile, $result, FILE_APPEND);
        return $return;
    }
}
// Блокирование Прокси

// Получение сетевых интерфейсов
if (!function_exists('getInterface')) {
    function getInterface($ip, $apiKey)
    {
        $data = [
            'ip' => $ip,
            'api_key' => $apiKey,
        ];

        $krakenService = new KrakenService();
        $result = $krakenService->getDevicesInterfaceList($data);
        $setIntRes = json_decode($result, JSON_UNESCAPED_UNICODE);

        foreach ($setIntRes as $key => $val) {
            if ($val['ifname'] == 'enp7s0') {
                unset($setIntRes[$key]);
            }
        }
        $setIntRes = array_values($setIntRes);
        return $setIntRes;
    }
}
// Получение сетевых интерфейсов

// Получение Модемов
if (!function_exists('getModems')) {
    function getModems($ip, $apiKey)
    {
        $data = [
            'ip' => $ip,
            'api_key' => $apiKey
        ];

        $krakenService = new KrakenService();
        $result = $krakenService->getDevicesModemType($data);

        return json_decode($result, JSON_UNESCAPED_UNICODE);
    }
}
// Получение Модемов

// Создание Прокси
if (!function_exists('createProxy')) {
    function createProxy($ip, $modem, $active = true, $type, $auth = 'strong', $isLocal = false, $maxconn = 0, $port, $apiKey, $authParams, $user, $modemId, $dateEndProxys)
    {
        // Генерируем пользователя
        $userLogin = User::find($user)->kraken_username;
        $userPass = isset(User::find($user)->kraken_password) ? User::find($user)->kraken_password : $userLogin;
        $hasUser = 0;

        $dataProxyAuthList = [
            'ip' => $ip,
            'api_key' => $apiKey,
        ];

        $krakenService = new KrakenService();
        $resultProxyAuthList = $krakenService->getProxyAuthList($dataProxyAuthList);
        $setIntRes = json_decode($resultProxyAuthList, JSON_UNESCAPED_UNICODE);

        file_put_contents('example.txt', $resultProxyAuthList);

        foreach ($setIntRes as $val) {
            if ($val['login'] == $userLogin) {
                $hasUser = $val['id'];
            }
        }
        if ($hasUser != 0) {
            $newUserProxy = $hasUser;
        } else {
            $dataProxyAuthAdd = [
                'ip' => $ip,
                'data' => [
                    'login' => $userLogin,
                    'password' => $userPass,
                    'bitrate_in' => 0,
                    'bitrate_out' => 0,
                ],
                'api_key' => $apiKey,
            ];

            $krakenService = new KrakenService();
            $resultProxyAuthAdd = $krakenService->getProxyAuthAdd($dataProxyAuthAdd);
            $responseCreateApiUserProxy = json_decode($resultProxyAuthAdd, JSON_UNESCAPED_UNICODE);

            file_put_contents('authadd.txt', $userLogin . " = " . $resultProxyAuthAdd); // Смотрим что пришло

            $newUserProxy = $responseCreateApiUserProxy['id'];
        }
        // Конец генерации пользователя

        $dataProxyAdd = [
            'ip' => $ip,
            'data' => [
                'modem' => $modem,
                'active' => $active,
                'type' => $type,
                'auth' => $auth,
                'port' => $port,
                'is_local' => $isLocal,
                'maxconn' => $maxconn,
                'is_allow' => true,
                'is_ports' => true,
                'auth_params' => [$newUserProxy],
                "allow" => [],
                "targetport" => []
            ],
            'api_key' => $apiKey,
        ];

        $krakenService = new KrakenService();
        $resultProxyAdd = $krakenService->getProxyAdd($dataProxyAdd);
        $responseCreateApi = json_decode($resultProxyAdd, JSON_UNESCAPED_UNICODE);

        $hasErrors = Arr::has($responseCreateApi, 'error');
        if ($hasErrors) {
            $return = 'Ошибка создания';
        } else if (!Arr::has($responseCreateApi, 'id')) {
            $return = json_encode($responseCreateApi) . '---' . json_encode($setIntRes);
            $return = 'Ошибка создания';
        } else {
            $userPassword = User::find($user)->kraken_password;
            $current = $dateEndProxys ?: Carbon::now();
            $newProxy = new Proxy;
            $newProxy->active = $active;
            $newProxy->type = $type;
            $newProxy->auth = $auth;
            $newProxy->number_proxy = $port;
            $newProxy->ifname = null;
            $newProxy->user_id = $user;
            $newProxy->modem_id = $modemId;
            $newProxy->date_end = $current;
            $newProxy->id_kraken = $responseCreateApi['id'];
            $newProxy->id_user_proxy_kraken = $newUserProxy;
            $newProxy->login_user_proxy_kraken = $userLogin;
            if ($userPassword != $userLogin) {
                $newProxy->password_user_proxy_kraken = $userPassword;
            } else {
                $newProxy->password_user_proxy_kraken = $userLogin;
            }
            $newProxy->save();
            $return = 'done';
        }

        return $return;
    }
}
// Создание Прокси

// Удаление Прокси
if (!function_exists('deliteProxy')) {
    function deliteProxy($ip, $idProxyKraken, $apiKey, $idProxy)
    {
        $data = [
            'ip' => $ip,
            'data' => [
                'id' => [$idProxyKraken]
            ],
            'api_key' => $apiKey,
        ];

        $krakenService = new KrakenService();
        $resultProxyAdd = $krakenService->getProxyDel($data);
        $responseCreateApi = json_decode($resultProxyAdd, JSON_UNESCAPED_UNICODE);

        $hasErrors = Arr::has($responseCreateApi, 'error');

        $return = true;
        Proxy::find($idProxy)->delete();

        return $return;
    }
}
// Удаление Прокси

// Покупка Прокси
if (!function_exists('purchaseProxy')) {
    function purchaseProxy($price, $count, $type, $userId, $idKrakenUser, $apiKey, $dateEndProxys, $country)
    {
        $servers = Server::where('country', '=', $country)->get();
        $ipSetting = $servers[0]->data['url'];
        $loginSetting = $servers[0]->data['login'];
        $passwordSetting = $servers[0]->data['password'];
        $auth = 'strong';
        $createStoreProxy = '';
        $portNumberSOCKS = '';
        $edPUA = '';
        $return = false;
        $countdone = 1;
        $countdoneWhile = 0;
        foreach ($servers as $key => $server) {

            $ipSetting = $server->data['url'];
            $loginSetting = $server->data['login'];
            $passwordSetting = $server->data['password'];

            $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);

            // Диапозон
            $httpMin = $server->data['httpmin'];
            $httpMax = $server->data['httpmax'];
            $socksMin = $server->data['socksmin'];
            $socksMax = $server->data['socksmax'];

            $modems = Modem::where('type_pay', $type)->where('server_id', $server->id)->get()->where('proxyfull', '!=', 'full')->toArray();
            $modems = array_values($modems);
            $countNotFullModems = count($modems);
            if (count($modems) >= $count) {
                foreach ($modems as $key => $value) {
                    if ($countdone <= $count) {
                        ++$countdone;
                        $portNumberHTTP = rand($httpMin, $httpMax);
                        $createStoreProxy = createProxy($ipSetting, $value['id_kraken'], true, 'http', $auth, false, 0, $portNumberHTTP, $apiKey, $idKrakenUser, $userId, $value['id'], $dateEndProxys);
                        $countdoneWhile .= $createStoreProxy . ',';
                        // Проверяем, создался ли прокси-сервер.
                        // Если не создался, то генерируем новый порт и пытаемся создать прокси-сервер снова.
                        while ($createStoreProxy !== 'done') {
                            $countdoneWhile .= $createStoreProxy . ',';
                            $portNumberHTTP = rand($httpMin, $httpMax);
                            $createStoreProxy = createProxy($ipSetting, $value['id_kraken'], true, 'http', $auth, false, 0, $portNumberHTTP, $apiKey, $idKrakenUser, $userId, $value['id'], $dateEndProxys);
                        }

                        $portNumberSOCKS = rand($socksMin, $socksMax);
                        $createStoreProxySOCKS = createProxy($ipSetting, $value['id_kraken'], true, 'socks', $auth, false, 0, $portNumberSOCKS, $apiKey, $idKrakenUser, $userId, $value['id'], $dateEndProxys);
                        $countdoneWhile .= '|' . $createStoreProxySOCKS;
                        // Проверяем, создался ли прокси-сервер.
                        // Если не создался, то генерируем новый порт и пытаемся создать прокси-сервер снова.
                        while ($createStoreProxySOCKS !== 'done') {
                            $countdoneWhile .= '|' . $createStoreProxySOCKS;
                            $portNumberSOCKS = rand($socksMin, $socksMax);
                            $createStoreProxySOCKS = createProxy($ipSetting, $value['id_kraken'], true, 'socks', $auth, false, 0, $portNumberSOCKS, $apiKey, $idKrakenUser, $userId, $value['id'], $dateEndProxys);
                        }

                        $edPUA = editPortUsersApi($value['id_kraken'], $idKrakenUser, $apiKey, $ipSetting);
                    } else {
                        $portNumberSOCKS .= $key;
                    }
                }

                $return = 'done';
                break;
            } else {
                $return = 'в наличии ' . $countNotFullModems;
            }
        }
        
        return $return;
    }
}
// Покупка Прокси

// Покупка Прокси
if (!function_exists('debitingBalance')) {
    function debitingBalance($userID, $amount, $notes, $referral, $quantity = null, $duration =null, $promocode = null, $discount = null)
    {
        $amount = abs($amount);
        $user = User::find($userID);
        $newBalance = $user->balance - $amount;
        if ($referral !== null) {
            $referralUser = User::find($referral);

            if ($user->balance < $amount) {
                $remainingAmount = $amount - $user->balance;
                $user->balance = 0;
                $user->referral_balance -= $remainingAmount;
            } else {
                $user->balance -= $amount;
            }
        } else {
            $user->balance = $newBalance;
        }

        // $user->balance = $newBalance;
        $user->save();

        $historyModel = new HistoryOperation;
        $historyModel->type = 'minus';
        $historyModel->amount = $amount;
        $historyModel->notes = $notes;
        $historyModel->quantity = $quantity;
        $historyModel->duration = $duration;
        $historyModel->discount_amount = $discount;
        $historyModel->promocode = $promocode;
        $historyModel->user_id = $userID;
        $historyModel->save();

        $return = true;

        return $return;
    }
}
// Покупка Прокси

if (!function_exists('getIfname')) {
    function getIfname($ip, $apiKey)
    {
        $dataProxyAdd = [
            'ip' => $ip,
            'api_key' => $apiKey,
        ];

        $krakenService = new KrakenService();
        $proxyFingerprintPreRes = $krakenService->getDevicesOsfpList($dataProxyAdd);
        $proxyFingerprintRes = json_decode($proxyFingerprintPreRes, JSON_UNESCAPED_UNICODE);

        $FingerListOption = [];
        foreach ($proxyFingerprintRes as $val) {
            $FingerListOption[$val['id']] = $val['name'];
        }

        return $FingerListOption;
    }
}

if (!function_exists('transliterate')) {
    function transliterate($string)
    {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'shch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
            'Е' => 'E', 'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
            'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
            'У' => 'U', 'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts', 'Ч' => 'Ch',
            'Ш' => 'Sh', 'Щ' => 'Shch', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya'
        );
        $transliteratedString = strtr(mb_strtolower($string), $converter);
        return $transliteratedString;
    }
}

if (!function_exists('hasRussianLetters')) {
    function hasRussianLetters($string)
    {
        return preg_match('/[а-яё]/iu', $string);
    }
}

if (!function_exists('getUserAddApi')) {
    function getUserAddApi($userID, $ip, $apiKey, $name, $regpassCURL, $email)
    {
        $name = 'userproxy'.$userID;
        $regpassCURL = str_replace(' ', '', $regpassCURL);
        $password = Str::random(8, 'alnum');
        $email = 'userproxy'.$userID.'@'.$_SERVER['HTTP_HOST'];

        $data = [
            'ip' => $ip,
            'data' => [
                'username' => $name,
                'password' => $password,
                'per_password' => $password,
                'first_name' => '',
                'last_name' => '',
                "is_staff" => true,
                'email' => $email,
                'is_active' => true,
                'userprofile' => [
                    'timezone' => 'Europe/Moscow',
                    'phone' => ''
                ]
            ],
            'api_key' => $apiKey,
        ];

        $krakenService = new KrakenService();
        $result = $krakenService->getUsersAdd($data);
        $res2 = json_decode($result, JSON_UNESCAPED_UNICODE);

        file_put_contents('AddUserAutcjKrakenNew.txt', json_encode($result));
        $hasErrors = Arr::has($res2, 'error');

        if (!$hasErrors) {
            if (array_key_exists('id', $res2)) {
                $res2ID = $res2['id'];
                $user = User::find($userID);
                $user->id_kraken = $res2ID;
                $user->kraken_username = $name;
                $user->kraken_password = $regpassCURL;
                $user->save();
            } else {
                file_put_contents('AddUserAutcjKraken.txt', $res2['errors']);
                return $res2['errors'];
            }
        } else {
            $res2ID = '0';
        }
        return true;
    }
}
