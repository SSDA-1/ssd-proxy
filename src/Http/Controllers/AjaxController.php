<?php

namespace ssda1\proxies\Http\Controllers;

use ssda1\proxies\Models\User;
use ssda1\proxies\Models\Proxy;
use ssda1\proxies\Models\Modem;
use ssda1\proxies\Models\Server;
use ssda1\proxies\Models\Support;
use ssda1\proxies\Models\Referral;
use ssda1\proxies\Models\Promocode;
use ssda1\proxies\Models\ProcessLog;
use ssda1\proxies\Models\siteSetting;
use ssda1\proxies\Models\SettingKraken;
use ssda1\proxies\Models\SettingNotices;
use ssda1\proxies\Models\TariffSettings;
use ssda1\proxies\Models\SupportMassages;
use ssda1\proxies\Models\HistoryOperation;
use ssda1\proxies\Models\CountDaysDiscount;
use ssda1\proxies\Models\CountProxyDiscount;
use ssda1\proxies\Models\CountPairsProxyDiscount;
use ssda1\proxies\Service\ProcessLogService;
use ssda1\proxies\Service\ExportPortsService;
use ssda1\proxies\Service\ProxyGlobalService;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Hash;
use Carbon\Carbon;
use Telegram\Bot\Api;

class AjaxController extends Controller
{
    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }

    /**
     * Ajax Сохранение Общих настроек сайта.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function sendSupportMass(Request $request)
    {

        $link = mysqli_connect("app.comet-server.ru", "3820", "QmmMSy3gzDmKIKqIzL0ATEaYSMgGDk0T4JSCNvd0kUYywzBSA9UsaaTCrhad4R3d", "CometQL_v1");

        // Данные пользователя
        $user = Auth::user();
        $balance = $user->balance;
        $userID = $user->id;
        // Данные пользователя

        $returnArray = [];

        $input = $request->all();
        $id = $input['id'];
        $text = $input['text'];
        $newMass = new SupportMassages;
        $newMass->support_id = $id;
        $newMass->massage = $text;
        $newMass->admin = false;
        $newMass->save();
        $returnArray['status'] = true;
        $returnArray['operation'] = 'sending';

        /**
         * Отправка данных в канал с именем Pipe_name передаётся сообщение с именем event_name и содержимым указанным в поле message.
         */
        // $result = mysqli_query (  $link, 'INSERT INTO pipes_messages (name, event, message)VALUES("web_'.$id.'", "event_name", "'.$updateHTMLBid.'BYN")' );
        $result = mysqli_query($link, "INSERT INTO pipes_messages (name, event, message)VALUES('web_$id', 'event_name', \"{'text':'$text','date':'','admin': 'no'}\")");


        return $returnArray;
    }

    /**
     * Ajax Сохранение пользователя.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ControlSaveUser(Request $request)
    {

        // Данные пользователя
        $user = Auth::user();
        $balance = $user->balance;
        $userID = $user->id;
        // Данные пользователя

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $userID,
            'password' => 'same:confirm-password'
        ]);
        if ($request->input('telegram_chat_id')) {
            $this->validate($request, [
                'telegram_chat_id' => 'required|numeric',
            ]);
        }

        $returnArray = [];

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        try {
            $user->update($input);
        } catch (\Exception $exception) {
            $this->log(
                'Создание пользователя',
                "Ошибка! Пользователь $user->id c почтой $user->email не создан",
                'User creation',
                "Error! User $user->id with $user->email not created"
            );
        }

        $this->log(
            'Создание пользователя',
            "Успешно! Пользователь $user->id c почтой $user->email создан",
            'User creation',
            "Successful! User $user->id with $user->email created"
        );

        $returnArray['status'] = true;

        return $returnArray;
    }

    /**
     * Ajax продление Прокси.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ControlExtendProxy(Request $request)
    {
        // Данные пользователя
        $user = Auth::user();
        $balance = $user->balance;
        $userID = $user->id;
        // Данные пользователя

        // Данные интеграции
        $returnArray = [];
        $idReq = $request->input('id');
        $proxy = Proxy::find($idReq);
        $idProxy2 = $idReq + 1;
        $proxy2 = Proxy::find($idProxy2);
        // Данные интеграции
        $server = $proxy->modem->server;
        $usernameIntegration = $server->data['login'];
        $passwordIntegration = $server->data['password'];
        $ipIntegration = $server->data['url'];
        // Данные интеграции

        //Базовая цена
        $userProxyCount = count($user->proxys) / 2;
        $endDateDB = $proxy->date_end;
        $price = $proxy->price;
        $monthReq = $request->input('month');


        $countryReq = $server->country;
        $typeReq =  $proxy->modem->type_pay;
        $tariffId = $request->input('idt');
        $tariffSetting = TariffSettings::find(1);
        $tariffType = $tariffSetting->type_tariff; //тип тарифной системы
        $proxyType = $tariffSetting->type_proxy; //тип прокси
        $defaultCountry = $tariffSetting->default_country;

        if ($tariffType == 0) {
            $daysTariff = $tariffSetting->days_tariff;
            $countryTariff = [];
            foreach ($daysTariff as $tariff) {
                if ($tariff['country'] == $countryReq) {
                    $countryTariff = $tariff;
                    break;
                } elseif ($tariff['country'] == $defaultCountry and empty($countryTariff)) {
                    $countryTariff = $tariff;
                }
            }

            if (!empty($countryTariff)) {
                if (in_array($proxyType, ['general', $typeReq])) {
                    $price = (float)$countryTariff['general_price'];
                } elseif (in_array($proxyType, ['private', $typeReq])) {
                    $price = (float)$countryTariff['private_price'];
                } elseif ($proxyType == 'all') {
                    if ($typeReq == 'general') {
                        $price = (float)$countryTariff['general_price'];
                    } elseif ($typeReq == 'private') {
                        $price = (float)$countryTariff['private_price'];
                    }
                }
            }

            $price *= $monthReq;
        } else {
            $tariffs = $tariffSetting->tariff;
            $tariff = $tariffs[$tariffId];
            if (in_array($countryReq, $tariff['country'])) {
                $key = array_search($countryReq, $tariff['country']);
                if (in_array($proxyType, ['general', $typeReq])) {
                    $price = (float)$tariff['general_price'][$key];
                    $monthReq = $tariff['period'];
                } elseif (in_array($proxyType, ['private', $typeReq])) {
                    $price = (float)$tariff['private_price'][$key];
                    $monthReq = $tariff['period'];
                } elseif ($proxyType == 'all') {
                    if ($typeReq == 'general') {
                        $price = (float)$tariff['general_price'][$key];
                        $monthReq = $tariff['period'];
                    } elseif ($typeReq == 'private') {
                        $price = (float)$tariff['private_price'][$key];
                        $monthReq = $tariff['period'];
                    }
                }
            }
        }

        if (empty($price)) {
            $this->log(
                'Продление прокси',
                "Ошибка! Некорректная цена",
                'Proxy extension',
                "Error! Incorrect price"
            );

            $returnArray['status'] = false;
            $returnArray['modal'] = true;
            $returnArray['massage'] = 'Некорректная цена';
            $returnArray['title'] = 'Ошибка продления';
        }


        $returnArray['month'] = $monthReq;
        //Базовая цена
        $basePrice = $price;

        //Цена со скидкой
        $finalDiscount = 0.00;
        $discount = 0.00;

        //скидка за количество активных пар прокси
        $countPairsProxyDiscount = CountPairsProxyDiscount::where('count_pairs', '<=', $userProxyCount)
            ->orderBy('count_pairs', 'desc')
            ->first();
        $discount = empty($countPairsProxyDiscount) ? 0 : $countPairsProxyDiscount->discount_buy;
        if (!empty($discount)) {
            $price -= $price * $discount / 100;
        }
        $finalDiscount = (float) (1 - $price / $basePrice);
        //Цена со скидкой

        if ($price <= $balance) {
            $current = Carbon::make($endDateDB)->addDays($monthReq);
            // $current = new Carbon::now();
            $proxy->date_end = $current;
            $proxy->save();
            // Второй прокси
            $proxy2->date_end = $current;
            $proxy2->save();
            debitingBalance($userID, $price, 'Продление прокси', null, 1, $monthReq, null, $finalDiscount);

            $settingNotices = SettingNotices::find(1);
            $admins = User::all();

            foreach ($admins as $admin) {
                if ($admin->hasRole('Admin')) {
                    $chatId = $admin->telegram_chat_id;
                    if ($chatId and $settingNotices->telegram_check == 1) {
                        $telegram = new Api($settingNotices->telegram_token);

                        $text1 = "";
                        $email = $user->email;
                        $tg = $user->telegram_name;
                        if ($tg)
                            $text1 = "$email / $tg";
                        else
                            $text1 = "$email";

                        $portName = $proxy->modem->name;

                        $telegram->sendMessage([
                            'chat_id' => $chatId,
                            'text' => "Прокси успешно продлен!\nПорты:$portName\nПокупатель: $text1\nЦена: $price\nПродлили до: $proxy->date_end"
                        ]);
                    }
                }
            }

            $this->log(
                'Продление прокси',
                "Успешно! Прокси продлен! Пользователь $user->email продлил прокси ($proxy->id, $proxy2->id) до: $proxy->date_end",
                'Proxy extension',
                "Successful! Extended proxy! $user->email extended proxy ($proxy->id, $proxy2->id) to: $proxy->date_end"
            );

            $auth = 'strong';
            $apiKey = getToken($ipIntegration, $usernameIntegration, $passwordIntegration);
            if (!$proxy->active || !$proxy2->active) {
                blockProxy($ipIntegration, true, $proxy->type, $auth, false, $proxy->maxconn, $proxy->number_proxy, $apiKey, $proxy->user->name, $proxy->user->id, $proxy->modem->id_kraken, $proxy->id, $proxy->id_kraken, $proxy->date_end);
                blockProxy($ipIntegration, true, $proxy2->type, $auth, false, $proxy2->maxconn, $proxy2->number_proxy, $apiKey, $proxy2->user->name, $proxy2->user->id, $proxy2->modem->id_kraken, $proxy2->id, $proxy2->id_kraken, $proxy2->date_end);
                $proxy->active = 1;
                $proxy2->active = 1;
                $proxy->save();
                $proxy2->save();
            }

            $returnArray['status'] = true;
            $returnArray['modal'] = true;
            $returnArray['massage'] = 'Прокси продлён';
            $returnArray['title'] = 'Успешно';
        } else {
            $this->log(
                'Продление прокси',
                "Ошибка! Недостаточно средств на балансе у пользователя $user->id с почтой $user->email",
                'Proxy extension',
                "Error! $user->id with $user->email insufficient balance"
            );

            $returnArray['status'] = false;
            $returnArray['modal'] = true;
            $returnArray['massage'] = 'Недостаточно средств на балансе';
            $returnArray['title'] = 'Ошибка продления';
        }

        return $returnArray;
    }

    /**
     * Ajax массовое продление Прокси.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multiControlExtendProxy(Request $request)
    {
        // Данные пользователя
        $user = Auth::user();
        $balance = $user->balance;
        $userID = $user->id;
        // Данные пользователя

        $siteSetting = siteSetting::find(1);
        $referral_balance = $user->referral_balance;
        // Раздельный баланс или нет
        if ($siteSetting->referral_balance_enabled == 1) {
            $balance = $balance + $referral_balance;
        }

        // Данные интеграции
        $returnArray = [];
        $idReqs = $request->input('ids');
        $idReqs = explode(', ', $idReqs);
        $fullPrice = 0;

        foreach ($idReqs as $idReq) {
            $proxy = Proxy::find($idReq);
            $fullPrice += $proxy->price;
        }

        if ($fullPrice > $balance) {
            /*$this->log(
                'Продление прокси',
                "Ошибка! Недостаточно средств на балансе у пользователя $user->id с почтой $user->email",
                'Proxy extension',
                "Error! $user->id with $user->email insufficient balance"
            );*/

            $returnArray['status'] = false;
            $returnArray['modal'] = true;
            $returnArray['massage'] = 'Недостаточно средств на балансе';
            $returnArray['title'] = 'Ошибка продления';

            return $returnArray;
        }

        foreach ($idReqs as $idReq) {
            $proxy = Proxy::find($idReq);
            $idProxy2 = $idReq - 1;
            $proxy2 = Proxy::find($idProxy2);
            // Данные интеграции
            $server = $proxy->modem->server;
            $usernameIntegration = $server->data['login'];
            $passwordIntegration = $server->data['password'];
            $ipIntegration = $server->data['url'];
            // Данные интеграции

            //Базовая цена
            $userProxyCount = count($user->proxys) / 2;
            $endDateDB = $proxy->date_end;
            //$price = $proxy->price;
            $monthReq = $request->input('month');


            $countryReq = $server->country;
            $typeReq = $proxy->modem->type_pay;
            $tariffId = $request->input('idt');
            $tariffSetting = TariffSettings::find(1);
            $tariffType = $tariffSetting->type_tariff; //тип тарифной системы
            $proxyType = $tariffSetting->type_proxy; //тип прокси
            $defaultCountry = $tariffSetting->default_country;

            if ($tariffType == 0) {
                $daysTariff = $tariffSetting->days_tariff;
                $countryTariff = [];
                foreach ($daysTariff as $tariff) {
                    if ($tariff['country'] == $countryReq) {
                        $countryTariff = $tariff;
                        break;
                    } elseif ($tariff['country'] == $defaultCountry and empty($countryTariff)) {
                        $countryTariff = $tariff;
                    }
                }

                if (!empty($countryTariff)) {
                    if (in_array($proxyType, ['general', $typeReq])) {
                        $price = (float)$countryTariff['general_price'];
                    } elseif (in_array($proxyType, ['private', $typeReq])) {
                        $price = (float)$countryTariff['private_price'];
                    } elseif ($proxyType == 'all') {
                        if ($typeReq == 'general') {
                            $price = (float)$countryTariff['general_price'];
                        } elseif ($typeReq == 'private') {
                            $price = (float)$countryTariff['private_price'];
                        }
                    }
                }

                $price *= $monthReq;
            } else {
                $tariffs = $tariffSetting->tariff;
                $tariff = $tariffs[$tariffId];
                if (in_array($countryReq, $tariff['country'])) {
                    $key = array_search($countryReq, $tariff['country']);
                    if (in_array($proxyType, ['general', $typeReq])) {
                        $price = (float)$tariff['general_price'][$key];
                        $monthReq = $tariff['period'];
                    } elseif (in_array($proxyType, ['private', $typeReq])) {
                        $price = (float)$tariff['private_price'][$key];
                        $monthReq = $tariff['period'];
                    } elseif ($proxyType == 'all') {
                        if ($typeReq == 'general') {
                            $price = (float)$tariff['general_price'][$key];
                            $monthReq = $tariff['period'];
                        } elseif ($typeReq == 'private') {
                            $price = (float)$tariff['private_price'][$key];
                            $monthReq = $tariff['period'];
                        }
                    }
                }
            }

            if (empty($price)) {
                $this->log(
                    'Продление прокси',
                    "Ошибка! Некорректная цена",
                    'Proxy extension',
                    "Error! Incorrect price"
                );

                $returnArray['status'] = false;
                $returnArray['modal'] = true;
                $returnArray['massage'] = 'Некорректная цена';
                $returnArray['title'] = 'Ошибка продления';
            }

            $returnArray['month'] = $monthReq;
            //Базовая цена
            $basePrice = $price;

            //Цена со скидкой
            $finalDiscount = 0.00;
            $discount = 0.00;

            //скидка за количество активных пар прокси
            $countPairsProxyDiscount = CountPairsProxyDiscount::where('count_pairs', '<=', $userProxyCount)
                ->orderBy('count_pairs', 'desc')
                ->first();
            $discount = empty($countPairsProxyDiscount) ? 0 : $countPairsProxyDiscount->discount_buy;
            if (!empty($discount)) {
                $price -= $price * $discount / 100;
            }
            $finalDiscount = (float)(1 - $price / $basePrice);
            //Цена со скидкой

            if ($price <= $balance) {
                $current = Carbon::make($endDateDB)->addDays($monthReq);
                // $current = new Carbon::now();
                $proxy->date_end = $current;
                $proxy->save();
                // Второй прокси
                $proxy2->date_end = $current;
                $proxy2->save();

                if ($siteSetting->referral_balance_enabled == 1) {
                    debitingBalance($userID, $price, 'Продление прокси', 'referral', 1, $monthReq, null, $finalDiscount);
                } else {
                    debitingBalance($userID, $price, 'Продление прокси', null, 1, $monthReq, null, $finalDiscount);
                }

                $settingNotices = SettingNotices::find(1);
                $admins = User::all();

                foreach ($admins as $admin) {
                    if ($admin->hasRole('Admin')) {
                        $chatId = $admin->telegram_chat_id;
                        if ($chatId and $settingNotices->telegram_check == 1) {
                            $telegram = new Api($settingNotices->telegram_token);

                            $text1 = "";
                            $email = $user->email;
                            $tg = $user->telegram_name;
                            if ($tg)
                                $text1 = "$email / $tg";
                            else
                                $text1 = "$email";

                            $portName = $proxy->modem->name;

                            $telegram->sendMessage([
                                'chat_id' => $chatId,
                                'text' => "Прокси успешно продлен!\nПорты:$portName\nПокупатель: $text1\nЦена: $price\nПродлили до: $proxy->date_end"
                            ]);
                        }
                    }
                }
                /*
                $this->log(
                    'Продление прокси',
                    "Успешно! Прокси продлен! Пользователь $user->email продлил прокси ($proxy->id, $proxy2->id) до: $proxy->date_end",
                    'Proxy extension',
                    "Successful! Extended proxy! $user->email extended proxy ($proxy->id, $proxy2->id) to: $proxy->date_end"
                );*/

                $auth = 'strong';
                $apiKey = getToken($ipIntegration, $usernameIntegration, $passwordIntegration);
                if (!$proxy->active || !$proxy2->active) {
                    blockProxy($ipIntegration, true, $proxy->type, $auth, false, $proxy->maxconn, $proxy->number_proxy, $apiKey, $proxy->user->name, $proxy->user->id, $proxy->modem->id_kraken, $proxy->id, $proxy->id_kraken, $proxy->date_end);
                    blockProxy($ipIntegration, true, $proxy2->type, $auth, false, $proxy2->maxconn, $proxy2->number_proxy, $apiKey, $proxy2->user->name, $proxy2->user->id, $proxy2->modem->id_kraken, $proxy2->id, $proxy2->id_kraken, $proxy2->date_end);
                    $proxy->active = 1;
                    $proxy2->active = 1;
                    $proxy->save();
                    $proxy2->save();
                }
            } else {
                /*$this->log(
                    'Продление прокси',
                    "Ошибка! Недостаточно средств на балансе у пользователя $user->id с почтой $user->email",
                    'Proxy extension',
                    "Error! $user->id with $user->email insufficient balance"
                );*/

                $returnArray['status'] = false;
                $returnArray['modal'] = true;
                $returnArray['massage'] = 'Недостаточно средств на балансе';
                $returnArray['title'] = 'Ошибка продления';

                return $returnArray;
            }
        }

        $returnArray['status'] = true;
        $returnArray['modal'] = true;
        $returnArray['massage'] = 'Прокси продлён';
        $returnArray['title'] = 'Успешно';

        return $returnArray;
    }

    /**
     * Ajax Смена IP.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multiChangeIP(Request $request)
    {
        $returnArray = [];
        $returnArray['status'] = true;
        $idReqs = $request->input('ids');
        $idReqs = explode(',', $idReqs);

        foreach ($idReqs as $idReq) {
            $proxy = Proxy::find($idReq);
            $idProxy2 = $idReq - 1;
            $proxy2 = Proxy::find($idProxy2);

            $proxyGlobal = new ProxyGlobalService();
            $export = $proxyGlobal->changeIP($idProxy2, $proxy);

            if (!$export) {
                $returnArray['status'] = false;

                /*$this->log(
                    'Смена IP',
                    "Ошибка! Не удалось сменить IP прокси ($proxy, $proxy2)",
                    'Change of IP',
                    "Error! Could not change IP proxy ($proxy, $proxy2)"
                );*/
            } else {
                /*$this->log(
                    'Смена IP',
                    "Успешно! Смена IP адреса прокси ($proxy, $proxy2)",
                    'Change of IP',
                    "Successful! Change proxy IP address ($proxy, $proxy2)"
                );*/
                $returnArray['data'] = $export;
            }
        }

        return $returnArray;
    }

    /**
     * Ajax Массое изменение времени смена IP.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multiChangeTimeIP(Request $request)
    {
        $returnArray = [];
        $returnArray['status'] = true;
        $idReqs = $request->input('ids');
        $idReqs = explode(',', $idReqs);
        $timeReqs = $request['time'];

        foreach ($idReqs as $key => $idReq) {
            $proxy = Proxy::find($idReq);
            $idProxy2 = $idReq - 1;
            $proxy2 = Proxy::find($idProxy2);

            $reconnect_intervalReq = $timeReqs;
            $id = $proxy->modem->id_kraken;
            $namePort = $proxy->modem->name;
            $modelReq = $proxy->modem->type;
            $interfaceReq = $proxy->modem->ifname;
            $usersReq = $proxy->modem->users;
            $typePay = $proxy->modem->type_pay;
            $maxUsers = $proxy->modem->max_users;
            $settingModel = $proxy->modem->server;
            $server = $settingModel->id;
            $ipSetting = $settingModel->data['url'];
            $loginSetting = $settingModel->data['login'];
            $passwordSetting = $settingModel->data['password'];

            $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);

            /*if (!$apiKey) {
                $this->log(
                    'Редактирование Прокси в Панели пользователя',
                    "Ошибка! Не удалось получить токен",
                    'Editing Proxy in User Panel',
                    "Error! Could not get token"
                );
            }*/

            $changeipReq = $proxy->modem->reconnect_type;
            $osfp = $proxy->modem->osfp;
            $locked_ip_type_change = $proxy->modem->locked_ip_type_change;

            $editStorePort = editPort($id, $ipSetting, $namePort, $interfaceReq, $modelReq, true, false, $osfp, 3, 0, "lte", $reconnect_intervalReq, $changeipReq, '20', $apiKey, $usersReq, $typePay, $maxUsers, $server, $locked_ip_type_change);

            /*$this->log(
                'Редактирование Прокси в Панели пользователя',
                "$editStorePort",
                'Editing Proxy in User Panel',
                "$editStorePort"
            );*/
            $returnArray['tut'] = 'Время изменено у выбранных прокси';
        }

        return $returnArray;
    }

    /**
     * Ajax Массое изменение времени смена IP.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function multiDownload(Request $request)
    {
        $returnArray = [];
        $returnArray['status'] = true;
        $idReqs = $request->input('ids');
        $idReqs = explode(',', $idReqs);
        $user = Auth::user();
        $content = "";

        foreach ($idReqs as $idReq) {
            $proxy = Proxy::find($idReq);
            $idProxy2 = $idReq - 1;
            $proxy2 = Proxy::find($idProxy2);

            if ($proxy->user->id == $user->id) {
                $protocol = $proxy->type;
                $login = $proxy->login_user_proxy_kraken ?: $proxy->user->kraken_username;
                $password = $proxy->password_user_proxy_kraken ?: $proxy->user->kraken_username;
                $ip = parse_url($proxy->modem->server->data['url'], PHP_URL_HOST);
                $port = $proxy->number_proxy;
                $protocol2 = $proxy2->type;
                $login2 = $proxy2->login_user_proxy_kraken ?: $proxy2->user->kraken_username;
                $password2 = $proxy2->password_user_proxy_kraken ?: $proxy2->user->kraken_username;
                $ip2 = parse_url($proxy2->modem->server->data['url'], PHP_URL_HOST);
                $port2 = $proxy2->number_proxy;
                $content .= "$protocol://$login:$password@$ip:$port\n$protocol2://$login2:$password2@$ip2$port2";
            } else {
                $returnArray['status'] = false;
            }
        }

        $fileName = uniqid() . '.txt';
        $tempFilePath = tempnam(sys_get_temp_dir(), 'tempfile_');
        file_put_contents($tempFilePath, $content);

        return response()->download($tempFilePath, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Скачивание файла прокси.
     *
     */
    public function download(Request $request, $id)
    {
        $user = Auth::user();
        $proxy = Proxy::find($id);
        $idProxy2 = $id + 1;
        $proxy2 = Proxy::find($idProxy2);

        if ($proxy->user->id == $user->id) {
            $protocol = $proxy->type;
            $login = $proxy->login_user_proxy_kraken ?: $proxy->user->kraken_username;
            $password = $proxy->password_user_proxy_kraken ?: $proxy->user->kraken_username;
            $ip = parse_url($proxy->modem->server->data['url'], PHP_URL_HOST);
            $port = $proxy->number_proxy;
            $protocol2 = $proxy2->type;
            $login2 = $proxy2->login_user_proxy_kraken ?: $proxy2->user->kraken_username;
            $password2 = $proxy2->password_user_proxy_kraken ?: $proxy2->user->kraken_username;
            $ip2 = parse_url($proxy2->modem->server->data['url'], PHP_URL_HOST);
            $port2 = $proxy2->number_proxy;
            $content = "$protocol://$login:$password@$ip:$port\n$protocol2://$login2:$password2@$ip2:$port2";

            $tempFilePath = tempnam(sys_get_temp_dir(), 'tempfile_');
            file_put_contents($tempFilePath, $content);

            $fileName = uniqid() . '.txt';
            return response()->download($tempFilePath, $fileName)->deleteFileAfterSend(true);
        } else {
            return response()->json(false);
        }
    }


    /**
     * Ajax перезапуск модема.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function restartModem(Request $request, $id)
    {
        $returnArray = [];
        $returnArray['status'] = true;
        $proxyGlobal = new ProxyGlobalService();
        $modem = Modem::find($id);
        $export = $proxyGlobal->restart($modem);

        if (!$export) {
            $this->log(
                'Перезапуск модема',
                "Ошибка! Не удалось перезпустить модем $modem->id",
                'Modem restart',
                "Error! Failed to restart the $modem->id"
            );
        } else {
            $this->log(
                'Перезапуск модема',
                "Успешно! Модем $modem->id",
                'Modem restart',
                "Success! Modem $modem->id"
            );
        }

        $returnArray['export'] = $export;

        return $returnArray;
    }

    /**
     * Ajax автопродление.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function autopayProxy(Request $request, $id)
    {
        $returnArray = [];
        $returnArray['status'] = true;
        $proxyGlobal = new ProxyGlobalService();
        $proxy = Proxy::find($id);
        $proxy2 = Proxy::find($id + 1);

        if (empty($proxy) or empty($proxy2)) {
            $this->log(
                'Автопродление',
                "Ошибка! не удалось найти прокси",
                'Auto-renew',
                "Error! could not find proxy"
            );
        }

        $monthReq = $request->input('month');
        $tariffId = $request->input('idt');
        //$price = $proxy->price;

        if (!empty($tariffId)) {
            $monthReq = $request->input('month');
            $server = $proxy->modem->server;
            $countryReq = $server->country;
            $typeReq =  $proxy->modem->type_pay;
            $tariffSetting = TariffSettings::find(1);
            $tariffType = $tariffSetting->type_tariff; //тип тарифной системы
            $proxyType = $tariffSetting->type_proxy; //тип прокси
            $defaultCountry = $tariffSetting->default_country;

            if ($tariffType == 0) {
                $daysTariff = $tariffSetting->days_tariff;
                $countryTariff = [];
                foreach ($daysTariff as $tariff) {
                    if ($tariff['country'] == $countryReq) {
                        $countryTariff = $tariff;
                        break;
                    } elseif ($tariff['country'] == $defaultCountry and empty($countryTariff)) {
                        $countryTariff = $tariff;
                    }
                }

                if (!empty($countryTariff)) {
                    if (in_array($proxyType, ['general', $typeReq])) {
                        $price = (float)$countryTariff['general_price'];
                    } elseif (in_array($proxyType, ['private', $typeReq])) {
                        $price = (float)$countryTariff['private_price'];
                    } elseif ($proxyType == 'all') {
                        if ($typeReq == 'general') {
                            $price = (float)$countryTariff['general_price'];
                        } elseif ($typeReq == 'private') {
                            $price = (float)$countryTariff['private_price'];
                        }
                    }
                }

                $price *= $monthReq;
            } else {
                $tariffs = $tariffSetting->tariff;
                $tariff = $tariffs[$tariffId];
                if (in_array($countryReq, $tariff['country'])) {
                    $key = array_search($countryReq, $tariff['country']);
                    if (in_array($proxyType, ['general', $typeReq])) {
                        $monthReq = $tariff['period'];
                        $price = (float)$tariff['general_price'][$key];
                    } elseif (in_array($proxyType, ['private', $typeReq])) {
                        $monthReq = $tariff['period'];
                        $price = (float)$tariff['private_price'][$key];
                    } elseif ($proxyType == 'all') {
                        if ($typeReq == 'general') {
                            $monthReq = $tariff['period'];
                            $price = (float)$tariff['general_price'][$key];
                        } elseif ($typeReq == 'private') {
                            $monthReq = $tariff['period'];
                            $price = (float)$tariff['private_price'][$key];
                        }
                    }
                }
            }
        }

        if ($proxy->autopay == 1) {
            $price = $price ?? null;
        }

        $autopay = $proxyGlobal->autopay($proxy, $proxy2, $monthReq, $price);

        $this->log(
            'Автопродление',
            "Успешно! Прокси ($proxy->id, $proxy2->id - $autopay)",
            'Auto-renew',
            "Successful! Proxy ($proxy->id, $proxy2->id - $autopay)"
        );

        $returnArray['description'] = $autopay;
        $returnArray['status'] = true;
        $returnArray['modal'] = true;
        $returnArray['massage'] = 'Автопродление включено';
        $returnArray['id'] = $proxy2->id;
        $returnArray['title'] = 'Успешно';

        return $returnArray;
    }

    /**
     * Ajax Смена IP.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changeIP(Request $request, $proxy, $proxy2)
    {
        $returnArray = [];
        $returnArray['status'] = true;
        $proxyGlobal = new ProxyGlobalService();
        $export = $proxyGlobal->changeIP($proxy, $proxy2);

        if (!$export) {
            $returnArray['status'] = false;

            $this->log(
                'Смена IP',
                "Ошибка! Не удалось сменить IP прокси ($proxy, $proxy2)",
                'Change of IP',
                "Error! Could not change IP proxy ($proxy, $proxy2)"
            );
        } else {
            $this->log(
                'Смена IP',
                "Успешно! Смена IP адреса прокси ($proxy, $proxy2)",
                'Change of IP',
                "Successful! Change proxy IP address ($proxy, $proxy2)"
            );
        }

        $returnArray['data'] = $export;
        return $returnArray;
    }

    /**
     * Ajax перезапуск модема.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getIntMod(Request $request, $id)
    {
        $returnArray = [];
        $returnArray['status'] = true;
        $firstServer = Server::find($id);

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
                'Перезапуск модема',
                "Ошибка! Не удалось получить токен",
                'Modem restart',
                "Error! Could not get token"
            );
        } elseif (empty($freeInterface)) {
            $this->log(
                'Перезапуск модема',
                "Ошибка! Не удалось получить сетевые интерфейсы",
                'Modem restart',
                "Error! Failed to get network interfaces"
            );
        } elseif (empty($modelModems)) {
            $this->log(
                'Перезапуск модема',
                "Ошибка! Не удалось получить модемы",
                'Modem restart',
                "Error! Could not get modems"
            );
        } else {
            $this->log(
                'Перезапуск модема',
                "Успешно!",
                'Modem restart',
                "Successfully!"
            );
        }

        $returnArray['interface'] = $freeInterface;
        $returnArray['model'] = $modelModems;

        return $returnArray;
    }

    /**
     * Ajax Добавление дней и времени проксям.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addTimeProxy(Request $request)
    {
        $returnArray = [];
        $returnArray['status'] = true;
        $returnArray['action'] = 'editCheckProxy';
        // Получить выбранные значения из input
        $selectedProxies = $request->input('selected-proxies', []);
        // Получить значение 'days' и 'time'
        $days = $request->input('days');
        $time = $request->input('time');
        list($hours, $minutes) = explode(':', $time);

        // Обойти массив выбранных значений
        foreach ($selectedProxies as $proxyId) {
            // Получить модель прокси по ID
            $proxy = Proxy::find($proxyId);
            $dateEnd = $proxy->date_end;
            // Изменяем дату окончания на выбранное количество дней и время
            if ($days and $time != '00:00') {
                $dateEnd = Carbon::parse($proxy->date_end)->addDays($days)->addHours($hours)->addMinutes($minutes)->format('Y-m-d H:i:s');
            } elseif ($days and $time == '00:00') {
                $dateEnd = Carbon::parse($proxy->date_end)->addDays($days)->format('Y-m-d H:i:s');
            } elseif (!$days and $time != '00:00') {
                $dateEnd = Carbon::parse($proxy->date_end)->addHours($hours)->addMinutes($minutes)->format('Y-m-d H:i:s');
            }

            try {
                $proxy->update(['date_end' => $dateEnd]);
            } catch (\Exception $exception) {
                $this->log(
                    'Добавление дней и времени проксям',
                    "Ошибка! Время у $proxy->id не добавлено",
                    'Adding days and time to proxy',
                    "Error! $proxy->id time not added"
                );
            }

            $this->log(
                'Добавление дней и времени проксям',
                "Успешно! Добавлено время $dateEnd у прокси $proxy->id",
                'Adding days and time to proxy',
                "Successful! Added $dateEnd time to proxy $proxy->id"
            );
        }

        return $returnArray;
    }

    /**
     * Ajax Редактирование Прокси в Панели пользователя.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ControlSaveEditProxy(Request $request)
    {
        $returnArray = [];
        $returnArray['status'] = true;
        $export = 'Пользователя не меняли';

        $idReq = $request->input('id');
        $id2Req = $request->input('id2');
        $returnArray['id'] = $idReq;
        $osfpReq = $request->input('ifname');
        $reconnect_typeReq = $request->input('reconnect_type');
        $reconnect_intervalReq = $request->input('reconnect_interval');

        // Смена логина и пароль от прокси
        $loginForm = $request->input('login');
        $passwordForm = $request->input('password');
        $password1Form = $request->input('password1');
        // Конец данных
        $proxy = Proxy::find($idReq);
        $proxy2 = Proxy::find($id2Req);
        $user = Auth::user();
        $id = $proxy->modem->id_kraken;
        $namePort = $proxy->modem->name;
        $modelReq = $proxy->modem->type;
        $interfaceReq = $proxy->modem->ifname;
        $usersReq = $proxy->modem->users;
        $typePay = $proxy->modem->type_pay;
        $maxUsers = $proxy->modem->max_users;
        $userProxyLoginDef = $proxy->login_user_proxy_kraken ?: $user->kraken_username;
        $userProxyPasswordDef = $proxy->password_user_proxy_kraken ?: $user->kraken_username;
        // $interfaceReq = $proxy->modem->ifname;

        // Смена Логина и пароля
        if ($userProxyLoginDef != $loginForm or $userProxyPasswordDef != $passwordForm) {
            $proxyGlobal = new ProxyGlobalService();
            $export = $proxyGlobal->changeUser($proxy, $loginForm, $passwordForm, $password1Form);
            $this->log(
                'Редактирование Прокси в Панели пользователя',
                "$export",
                'Editing Proxy in User Panel',
                "$export"
            );
            $export = $proxyGlobal->changeUser($proxy2, $loginForm, $passwordForm, $password1Form);
            $this->log(
                'Редактирование Прокси в Панели пользователя',
                "$export",
                'Editing Proxy in User Panel',
                "$export"
            );
        }

        $settingModel = $proxy->modem->server;
        $server = $settingModel->id;
        $ipSetting = $settingModel->data['url'];
        $loginSetting = $settingModel->data['login'];
        $passwordSetting = $settingModel->data['password'];
        $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);
        if (!$apiKey) {
            $this->log(
                'Редактирование Прокси в Панели пользователя',
                "Ошибка! Не удалось получить токен",
                'Editing Proxy in User Panel',
                "Error! Could not get token"
            );
        }

        $locked_ip_type_change = $proxy->modem->locked_ip_type_change != 0 ? 2 : 0; // Проверяем заблокирован ли прокси для смены типа ip

        if ($osfpReq) {
            $osfp = $osfpReq;
            $changeipReq = $proxy->modem->reconnect_type;
            $editStorePort = editPort($id, $ipSetting, $namePort, $interfaceReq, $modelReq, true, true, $osfp, 3, 0, "lte", '2592000', $changeipReq, '20', $apiKey, $usersReq, $typePay, $maxUsers, $server, $locked_ip_type_change);
            $this->log(
                'Редактирование Прокси в Панели пользователя',
                "$editStorePort",
                'Editing Proxy in User Panel',
                "$editStorePort"
            );
            $returnArray['tut'] = 'изменён отпечаок';
        } else if ($osfpReq == 'none') {
            $osfp = $osfpReq;
            $changeipReq = $proxy->modem->reconnect_type;
            $editStorePort = editPort($id, $ipSetting, $namePort, $interfaceReq, $modelReq, true, true, $osfp, 3, 0, "lte", '2592000', $changeipReq, '20', $apiKey, $usersReq, $typePay, $maxUsers, $server, $locked_ip_type_change);
            $this->log(
                'Редактирование Прокси в Панели пользователя',
                "$editStorePort",
                'Editing Proxy in User Panel',
                "$editStorePort"
            );
            $returnArray['tut'] = 'изменён отпечаок';
        }
        if ($reconnect_typeReq) {
            $osfp = $osfpReq ?: $proxy->modem->osfp;
            $changeipReq = $reconnect_typeReq;
            $returnArray['reconnect'] = $reconnect_typeReq;
            if ($osfp != null) {
                $editStorePort = editPort($id, $ipSetting, $namePort, $interfaceReq, $modelReq, true, true, $osfp, 3, 0, "lte", '2592000', $changeipReq, '20', $apiKey, $usersReq, $typePay, $maxUsers, $server, $locked_ip_type_change);
                $this->log(
                    'Редактирование Прокси в Панели пользователя',
                    "$editStorePort",
                    'Editing Proxy in User Panel',
                    "$editStorePort"
                );
            } else {
                $editStorePort = editPort($id, $ipSetting, $namePort, $interfaceReq, $modelReq, true, false, null, 3, 0, "lte", '2592000', $changeipReq, '20', $apiKey, $usersReq, $typePay, $maxUsers, $server, $locked_ip_type_change);
                $this->log(
                    'Редактирование Прокси в Панели пользователя',
                    "$editStorePort",
                    'Editing Proxy in User Panel',
                    "$editStorePort"
                );
            }

            $returnArray['tut'] = 'изменён реконект';
        }
        if ($reconnect_intervalReq) {
            $osfp = $osfpReq ?: $proxy->modem->osfp;
            $changeipReq = $reconnect_typeReq ?: $proxy->modem->reconnect_type;
            $editStorePort = editPort($id, $ipSetting, $namePort, $interfaceReq, $modelReq, true, true, $osfp, 3, 0, "lte", $reconnect_intervalReq, $changeipReq, '20', $apiKey, $usersReq, $typePay, $maxUsers, $server, $locked_ip_type_change);
            $this->log(
                'Редактирование Прокси в Панели пользователя',
                "$editStorePort",
                'Editing Proxy in User Panel',
                "$editStorePort"
            );
            $returnArray['tut'] = 'время изменено';
        }

        $returnArray['input'] = $osfpReq;
        $returnArray['userChange'] = $export;
        return $returnArray;
    }

    /**
     * Ajax Проверка промокода.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function PromocodeCheck(Request $request)
    {
        $data = $request->all();

        $promocodeReq = $data['promocode'];
        $countReq = $data['rent'];
        $monthReq = $data['quantity'];
        $userID = Auth::user()->id;

        // $promocode = Promocode::where('name', $promocodeReq)->first();
        $promocode = Promocode::where('name', $promocodeReq)
            ->where('min_quantity', '<=', $countReq)
            ->where('min_rent', '<=', $monthReq)
            ->where('date_end', '>', Carbon::parse(now()))
            ->where('is_active', true)
            ->first();
        if (!empty($promocode)) {
            $flag = true;
            if (Carbon::parse(now()) > Carbon::parse($promocode->date_end)) {
                $promocode->is_active = false;
                $flag = false;
            } elseif ($promocode->count_activated >= $promocode->max_activated) {
                $promocode->is_active = false;
                $flag = false;
            } elseif (!$promocode->multi_activating) {
                $countPromocode = HistoryOperation::where('user_id', $userID)
                    ->where('promocode', $promocode->name)
                    ->count();
                if ($countPromocode > 0)
                    $flag = false;
            }
            $promocode->save();
            if ($flag)
                return response()->json(['exists' => true, 'discount' => $promocode->discount]);
            else
                return response()->json(['exists' => false, 'discount' => 0]);
        } else {
            return response()->json(['exists' => false, 'discount' => 0]);
        }
    }

    /**
     * Ajax Покупка Прокси.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function HomeBuyProxy(Request $request)
    {

        $returnArray = [];
        $siteSetting = siteSetting::find(1);

        // Данные пользователя
        $user = Auth::user();
        $userID = $user->id;
        $balance = $user->balance;
        $referral_balance = $user->referral_balance;

        // Раздельный баланс или нет
        if ($siteSetting->referral_balance_enabled == 1) {
            $balance = $balance + $referral_balance;
        }

        $idKrakenUser = $user->id_kraken;
        // Данные пользователя

        // Данные с формы покупки
        $countryReq = $request->input('country');
        $monthReq = $request->input('month');
        $typeReq = $request->input('type');
        $countReq = $request->input('count');
        $tariffId = $request->input('id');

        //Проверка промокода + статус неактивный
        $promocodeReq = $request->input('promo');
        $promocode = Promocode::where('name', $promocodeReq)->first();
        if (!empty($promocode)) {
            $flag = false;
            if (Carbon::parse(now()) > Carbon::parse($promocode->date_end)) {
                $promocode->is_active = false;
                $flag = true;
            } elseif ($promocode->count_activated >= $promocode->max_activated) {
                $promocode->is_active = false;
                $flag = true;
            } elseif (!$promocode->multi_activating) {
                $countPromocode = HistoryOperation::where('user_id', $userID)
                    ->where('promocode', $promocode->name)
                    ->count();
                if ($countPromocode > 0)
                    $flag = true;
            }
            $promocode->save();

            if ($flag) {
                $returnArray['status'] = false;
                $returnArray['modal'] = true;
                $returnArray['massage'] = 'Неверный промокод';
                $returnArray['title'] = 'Промокод исстек';
                return $returnArray;
            }
        }

        //Проверка промокода + статус неактивный

        // Данные с формы покупки

        // Данные интеграции
        $servers = Server::where('country', $countryReq)->first();
        $usernameIntegration = $servers->data['login'];
        $passwordIntegration = $servers->data['password'];
        $ipIntegration = $servers->data['url'];
        // Данные интеграции

        $tariffSetting = TariffSettings::find(1);
        $tariffType = $tariffSetting->type_tariff; //тип тарифной системы
        $proxyType = $tariffSetting->type_proxy; //тип прокси
        $userProxyCount = count($user->proxys) / 2; //количество прокси пользователя
        $defaultCountry = $tariffSetting->default_country;
        $promocodeName = null;

        //Базовая цена
        $price = 0.00;
        if ($tariffType == 0) {
            $daysTariff = $tariffSetting->days_tariff;
            $countryTariff = [];
            foreach ($daysTariff as $tariff) {
                if ($tariff['country'] == $countryReq) {
                    $countryTariff = $tariff;
                    break;
                } elseif ($tariff['country'] == $defaultCountry and empty($countryTariff)) {
                    $countryTariff = $tariff;
                }
            }

            if (!empty($countryTariff)) {
                if (in_array($proxyType, ['general', $typeReq])) {
                    $price = (float)$countryTariff['general_price'];
                } elseif (in_array($proxyType, ['private', $typeReq])) {
                    $price = (float)$countryTariff['private_price'];
                } elseif ($proxyType == 'all') {
                    if ($typeReq == 'general') {
                        $price = (float)$countryTariff['general_price'];
                    } elseif ($typeReq == 'private') {
                        $price = (float)$countryTariff['private_price'];
                    }
                }
            }

            $price *= $monthReq;
        } else {
            $tariffs = $tariffSetting->tariff;
            $tariff = $tariffs[$tariffId];
            if (in_array($countryReq, $tariff['country'])) {
                $key = array_search($countryReq, $tariff['country']);
                if (in_array($proxyType, ['general', $typeReq])) {
                    $price = (float)$tariff['general_price'][$key];
                } elseif (in_array($proxyType, ['private', $typeReq])) {
                    $price = (float)$tariff['private_price'][$key];
                } elseif ($proxyType == 'all') {
                    if ($typeReq == 'general') {
                        $price = (float)$tariff['general_price'][$key];
                    } elseif ($typeReq == 'private') {
                        $price = (float)$tariff['private_price'][$key];
                    }
                }
            }
            $monthReq = $tariff['period'];
        }
        //Базовая цена
        $baseOnePrice = $price;
        $price *= $countReq;
        $basePrice = $price;

        //Цена со скидкой
        $finalDiscount = 0.00;
        $discounts = [];

        //скидка по промокоду
        if (!empty($promocode)) {
            $promocodeDiscount = Promocode::where('name', $promocodeReq)
                ->where('min_quantity', '<=', $countReq)
                ->where('min_rent', '<=', $monthReq)
                ->where('date_end', '>', Carbon::parse(now()))
                ->where('is_active', true)
                ->first();

            if (empty($promocodeDiscount)) {
                $returnArray['status'] = false;
                $returnArray['modal'] = true;
                $returnArray['massage'] = 'Неверный промокод';
                $returnArray['title'] = 'Ошибка ввода промокода';
                return $returnArray;
            }
            $discounts['amountPromocodeDiscount'] = [
                'discount' => $promocodeDiscount->discount,
                'multi' => $tariffSetting->promocode_discount
            ];
            $promocodeName = $promocodeDiscount->name;
        }

        //скидка за количество активных пар прокси
        $countPairsProxyDiscount = CountPairsProxyDiscount::where('count_pairs', '<=', $userProxyCount)
            ->orderBy('count_pairs', 'desc')
            ->first();
        $discounts['amountPairsProxyDiscount'] = [
            'discount' => empty($countPairsProxyDiscount) ? 0 : $countPairsProxyDiscount->discount_buy,
            'multi' => $tariffSetting->proxy_pairs_discount
        ];

        //скидка от количества дней
        $countDaysDiscount = CountDaysDiscount::where('days', '<=', $monthReq)
            ->where('type', $typeReq)
            ->where('country', $countryReq)
            ->orderBy('days', 'desc')
            ->first();
        $discounts['amountDaysDiscount'] = [
            'discount' => empty($countDaysDiscount) ? 0 : $countDaysDiscount->discount,
            'multi' => $tariffSetting->days_discount
        ];

        //скидка от количества прокси
        $countProxyDiscount = CountProxyDiscount::where('proxy', '<=', $countReq)
            ->where('type', $typeReq)
            ->where('country', $countryReq)
            ->orderBy('proxy', 'desc')
            ->first();
        $discounts['amountProxyDiscount'] = [
            'discount' => empty($countProxyDiscount) ? 0 : $countProxyDiscount->discount,
            'multi' => $tariffSetting->proxy_discount
        ];

        $discountsSum = [];
        foreach ($discounts as $key => $discount) {
            if ($discount['multi'] and $discount['discount']) {
                $discountsSum[$key] = $discount['discount'];
            }
        }

        if (!empty($promocodeReq)) {
            if ($tariffSetting->promocode_discount) {
                foreach ($discounts as $discount) {
                    if (!empty($discount['discount']) and $discount['multi']) {
                        $price -= $price * $discount['discount'] / 100;
                    }
                }
            } else {
                $price -= $price * $discounts['amountPromocodeDiscount']['discount'] / 100;
            }
        } else {
            if (empty($discountsSum)) {
                foreach ($discounts as $discount) {
                    if (!empty($discount['discount'])) {
                        $price -= $price * $discount['discount'] / 100;
                        break;
                    }
                }
            } else {
                foreach ($discountsSum as $discount) {
                    if (!empty($discount)) {
                        $price -= $price * $discount / 100;
                    }
                }
            }
        }
        $finalDiscount = (float) (1 - $price / $basePrice);
        //Цена со скидкой

        $tokenAdminKraken = getToken($ipIntegration, $usernameIntegration, $passwordIntegration);
        if (!$tokenAdminKraken) {
            $this->log(
                'Покупка прокси',
                "Ошибка! Не удалось получить токен",
                'Proxy purchase',
                "Error! Could not get token"
            );
        }

        if ($countReq == 0) {
            $this->log(
                'Покупка прокси',
                "Ошибка! Покупка доступна от 1 прокси",
                'Proxy purchase',
                "Error! Purchase available from 1 proxy"
            );

            $returnArray['status'] = false;
            $returnArray['modal'] = true;
            $returnArray['massage'] = 'Покупка доступна от 1 прокси';
            $returnArray['title'] = 'Ошибка покупки';
        } else if ($balance >= $price) {
            $countFreeBuy = 0;
            $dateEndProxys = Carbon::now()->addDays($monthReq);
            $countFreeBuy = purchaseProxy($price, $countReq, $typeReq, $userID, $idKrakenUser, $tokenAdminKraken, $dateEndProxys, $countryReq);

            if ($countFreeBuy == 'done') {

                if ($siteSetting->referral_balance_enabled == 1) {
                    debitingBalance($userID, $price, 'Покупка прокси', 'referral', $countReq, $monthReq, $promocodeName, $finalDiscount);
                } else {
                    debitingBalance($userID, $price, 'Покупка прокси', null, $countReq, $monthReq, $promocodeName, $finalDiscount);
                }

                if (!empty($siteSetting->deposit_percentage)) {
                    $referred = Referral::where('user_id', $user->id)->first();

                    if (!empty($referred)) {
                        $referrer = User::find($referred->referred_by);

                        if (!empty($referrer)) {
                            $beforeBalance = $referrer->referral_balance;
                            $referrer->referral_balance += $price * $siteSetting->deposit_percentage / 100;
                            $referrer->save();
                            $afterBalance = $referrer->referral_balance;

                            $historyModel = new HistoryOperation;
                            $historyModel->type = 'plus';
                            $historyModel->amount = $price * $siteSetting->deposit_percentage / 100;
                            $historyModel->notes = 'Реферальное пополнение';
                            $historyModel->country = $countryReq;
                            $historyModel->quantity = $countReq;
                            $historyModel->duration = $monthReq;
                            $historyModel->balance_before = $beforeBalance;
                            $historyModel->balance_after = $afterBalance;
                            $historyModel->user_id = $referrer->id;
                            $historyModel->referred_by = $user->id;
                            $historyModel->save();
                        }
                    }
                }

                $settingNotices = SettingNotices::find(1);
                $admins = User::all();

                foreach ($admins as $admin) {
                    if ($admin->hasRole('Admin')) {
                        $chatId = $admin->telegram_chat_id;
                        if ($chatId and $settingNotices->telegram_check == 1) {
                            $telegram = new Api($settingNotices->telegram_token);

                            $text1 = "";
                            $email = $user->email;
                            $tg = $user->telegram_name;
                            if ($tg)
                                $text1 = "$email / $tg";
                            else
                                $text1 = "$email";

                            $text2 = "";
                            $proxys = $user->proxys()->limit($countReq * 2)->get();
                            foreach ($proxys as $key => $proxy) {
                                $proxy->autopay_days = $monthReq;
                                $proxy->price = $baseOnePrice;
                                $proxy->save();
                                if ($key % 2 == 0) {
                                    $portName = $proxy->modem->name;
                                    $text2 .= " $portName";
                                }
                            }

                            $telegram->sendMessage([
                                'chat_id' => $chatId,
                                'text' => "Прокси успешно приобретён!\nПорты:$text2\nПокупатель: $text1\nЦена: $price\nКоличество: $countReq\nДата окончания: $dateEndProxys"
                            ]);
                        }
                    }
                }

                if ($promocode) {
                    $promocode->count_activated += 1;
                    $promocode->save();
                }
                $this->log(
                    'Покупка прокси',
                    "Успешно! Пользователь $user->id с почтой $user->email купил прокси",
                    'Proxy purchase',
                    "Successful! User $user->id with $user->email bought proxy"
                );

                $returnArray['status'] = true;
                $returnArray['modal'] = true;
                $returnArray['massage'] = 'Прокси успешно приобретён и добавлен в ваш профиль';
                $returnArray['title'] = 'Куплено';
            } else {
                $this->log(
                    'Покупка прокси',
                    "Ошибка! $countFreeBuy",
                    'Proxy purchase',
                    "Error! $countFreeBuy"
                );

                $returnArray['status'] = false;
                $returnArray['modal'] = true;
                $returnArray['massage'] = 'Произошла ошибка, ' . $countFreeBuy;
                $returnArray['title'] = 'Ошибка покупки';
                $returnArray['input'] = $request->all();
            }


            // $returnArray['price'] = $countFreeBuy;

        } else {
            $this->log(
                'Покупка прокси',
                "Ошибка! Недостаточно средств на балансе у пользователя $user->id с почтой $user->email",
                'Proxy purchase',
                "Error! $user->id with $user->email insufficient balance"
            );

            $returnArray['status'] = false;
            $returnArray['modal'] = true;
            $returnArray['massage'] = 'У вас недостаточно средств';
            $returnArray['title'] = 'Ошибка покупки';
        }

        // $returnArray['token'] = $tokenAdminKraken;

        return $returnArray;
    }

    /**
     * Ajax Просчет скидки при покупки прокси.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function HomeBuyProxyDiscount(Request $request)
    {
        // Данные пользователя
        $user = Auth::user();
        // Данные пользователя

        // Данные с формы покупки
        $data = $request->all();
        $countryReq = $data['country'];
        $monthReq = $data['month'];
        $typeReq = $data['type'];
        $countReq = $data['count'];

        // Данные с формы покупки
        $tariffSetting = TariffSettings::find(1);
        $userProxyCount = count($user->proxys) / 2; //количество прокси пользователя
        // Данные с формы покупки

        //Цена со скидкой
        $discounts = [];

        //скидка за количество активных пар прокси
        $countPairsProxyDiscount = CountPairsProxyDiscount::where('count_pairs', '<=', $userProxyCount)
            ->orderBy('count_pairs', 'desc')
            ->first();
        $discounts['amountPairsProxyDiscount'] = [
            'discount' => empty($countPairsProxyDiscount) ? 0 : $countPairsProxyDiscount->discount_buy,
            'multi' => $tariffSetting->proxy_pairs_discount
        ];

        //скидка от количества дней
        $countDaysDiscount = CountDaysDiscount::where('days', '<=', $monthReq)
            ->where('type', $typeReq)
            ->where('country', $countryReq)
            ->orderBy('days', 'desc')
            ->first();
        $discounts['amountDaysDiscount'] = [
            'discount' => empty($countDaysDiscount) ? 0 : $countDaysDiscount->discount,
            'multi' => $tariffSetting->days_discount
        ];

        //скидка от количества прокси
        $countProxyDiscount = CountProxyDiscount::where('proxy', '<=', $countReq)
            ->where('type', $typeReq)
            ->where('country', $countryReq)
            ->orderBy('proxy', 'desc')
            ->first();
        $discounts['amountProxyDiscount'] = [
            'discount' => empty($countProxyDiscount) ? 0 : $countProxyDiscount->discount,
            'multi' => $tariffSetting->proxy_discount
        ];

        $discountsSum = [];
        foreach ($discounts as $key => $discount) {
            if ($discount['multi'] and $discount['discount']) {
                $discountsSum[$key] = $discount['discount'];
            }
        }

        if (empty($discountsSum)) {
            foreach ($discounts as $discount) {
                if (!empty($discount['discount'])) {
                    $discountsSum = $discount;
                    break;
                }
            }
        }

        if (empty($discountsSum)) {
            $discountsSum = [];
        }

        return $discountsSum;
    }

    /**
     * Ajax Экспорт портов в админке.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPorts(Request $request)
    {
        $returnArray = [];
        $serverInput = $request->input('server');
        // Данные интеграции
        $server = Server::find($serverInput);
        $usernameIntegration = $server->data['login'];
        $passwordIntegration = $server->data['password'];
        $ipIntegration = $server->data['url'];

        // Данные интеграции
        $tokenAdminKraken = getToken($ipIntegration, $usernameIntegration, $passwordIntegration);
        if (!$tokenAdminKraken) {
            $this->log(
                'Экспорт портов в админке',
                "Ошибка! Не удалось получить токен",
                'Port export in administration',
                "Error! Could not get token"
            );
        }

        $export = new ExportPortsService();
        $export = $export->exportPorts($tokenAdminKraken, $ipIntegration, $serverInput);

        $this->log(
            'Экспорт портов в админке',
            "Успешно!",
            'Port export in administration',
            "Successful!"
        );

        $returnArray['status'] = true;
        $returnArray['ports'] = $export;

        return $returnArray;
    }

    /**
     * Ajax Экспорт прокси в админке.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportProxy(Request $request)
    {
        $returnArray = [];
        $serverInput = $request->input('server');
        // Данные интеграции
        $server = Server::find($serverInput);
        $usernameIntegration = $server->data['login'];
        $passwordIntegration = $server->data['password'];
        $ipIntegration = $server->data['url'];
        // Данные интеграции

        $tokenAdminKraken = getToken($ipIntegration, $usernameIntegration, $passwordIntegration);
        if (!$tokenAdminKraken) {
            $this->log(
                'Экспорт прокси в админке',
                "Ошибка! Не удалось получить токен",
                'Export proxy in admin',
                "Error! Could not get token"
            );
        }

        $export = new ExportPortsService();

        try {
            $export = $export->exportProxy($tokenAdminKraken, $ipIntegration);
        } catch (\Exception $exception) {
            $this->log(
                'Экспорт прокси в админке',
                "Ошибка!",
                'Export proxy in admin',
                "Error!"
            );
        }

        $this->log(
            'Экспорт прокси в админке',
            "Успешно!",
            'Export proxy in admin',
            "Successful!"
        );

        $returnArray['status'] = true;
        $returnArray['ports'] = $export;

        return $returnArray;
    }

    /**
     * Ajax Экспорт Пользователей в админке.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportUsers(Request $request)
    {
        $returnArray = [];
        $serverInput = $request->input('server');
        // Данные интеграции
        $server = Server::find($serverInput);
        $usernameIntegration = $server->data['login'];
        $passwordIntegration = $server->data['password'];
        $ipIntegration = $server->data['url'];
        // Данные интеграции
        $tokenAdminKraken = getToken($ipIntegration, $usernameIntegration, $passwordIntegration);
        if (!$tokenAdminKraken) {
            $this->log(
                'Экспорт пользователей в админке',
                "Ошибка! Не удалось получить токен",
                'Export users in admin',
                "Error! Could not get token"
            );
        }

        $export = new ExportPortsService();
        try {
            $export = $export->exportUsers($tokenAdminKraken, $ipIntegration);
        } catch (\Exception $exception) {
            $this->log(
                'Экспорт пользователей в админке',
                "Ошибка!",
                'Export users in admin',
                "Error!"
            );
        }

        $this->log(
            'Экспорт пользователей в админке',
            "Успешно!",
            'Export users in admin',
            "Successful!"
        );

        $returnArray['status'] = true;
        $returnArray['ports'] = $export;

        return $returnArray;
    }
}
