<?php

namespace ssd\proxies\Console\Commands;

use ssd\proxies\Models\SettingKraken;
use ssd\proxies\Models\siteSetting;
use ssd\proxies\Models\SettingNotices;
use ssd\proxies\Models\CountPairsProxyDiscount;
use ssd\proxies\Models\Proxy;
use ssd\proxies\Models\User;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Exception;
use Carbon\Carbon;
use NotificationChannels\Telegram\TelegramUpdates;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

class EndProxyCron extends Command
{
    /**
     * Название и подпись команды.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Command description';

    // /**
    //  * Создание новой команды.
    //  *
    //  * @return int
    //  */
    // public function handle()
    // {
    //     return Command::SUCCESS;
    // }



    /**

     * Исполнение консольной команды.

     *

     * @return mixed

     */

    public function handle()

    {

        \Log::info("Cron is working fine!");

        $settingNotices = SettingNotices::find(1);

        $proxys = Proxy::all();
        // Подключаемся к токену
        // $settingModel = SettingKraken::find(1);
        $ipSetting = '';
        $loginSetting = '';
        $passwordSetting = '';
        $deliteProxy = '';
        $auth = 'strong';

        foreach ($proxys as $key => $proxy) {
            $chatId = isset($proxy->user->telegram_chat_id) ? $proxy->user->telegram_chat_id : null;
            $nowTime = Carbon::now();
            $finishTime = Carbon::parse($proxy->date_end);
            if ($finishTime->getTimestamp() < $nowTime->getTimestamp()) {
                $totalDuration = $finishTime->floatDiffInDays($nowTime);

                if ($totalDuration >= 2) {

                    $server = $proxy->modem->server;
                    $ipSetting = $server->data['url'];
                    $loginSetting = $server->data['login'];
                    $passwordSetting = $server->data['password'];

                    $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);


                    $deliteProxy = deliteProxy($ipSetting, $proxy->id_kraken, $apiKey, $proxy->id);
                    $subject = "Мы вынуждены были удалить ваши прокси-серверы из-за того, что их аренда не была оплачена.";

                    if ($chatId and $settingNotices->telegram_check == 1) {
                        $telegram = new Api($settingNotices->telegram_token);
                        // Отправляем сообщение с chat_id пользователю
                        $telegram->sendMessage([
                            'chat_id' => $chatId,
                            'text' => $subject
                        ]);
                    }
                } elseif ($totalDuration >= 1) {
                    $server = $proxy->modem->server;
                    $ipSetting = $server->data['url'];
                    $loginSetting = $server->data['login'];
                    $passwordSetting = $server->data['password'];

                    $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);
                    //Базовая цена
                    $user = $proxy->user;
                    $settingData = SettingKraken::find(1);
                    $proxyType = $proxy->modem->type_pay;
                    $monthReq = $proxy->autopay_days;
                    $endDateDB = $proxy->date_end;
                    $userProxyCount = count($user->proxys) / 2;

                    $siteSetting = siteSetting::find(1);
                    $balance = $user->balance;
                    $referral_balance = $user->referral_balance;
                    // Раздельный баланс или нет
                    if ($siteSetting->referral_balance_enabled == 1) {
                        $balance = $balance + $referral_balance;
                    }

                    if ($proxy->autopay) {

                        $price = $proxy->price;
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

                        if ($balance >= $price) {
                            $current = Carbon::make($endDateDB)->addDays($monthReq);
                            $proxy2s = Proxy::where('date_end', $proxy->date_end)
                                ->where('modem_id', $proxy->modem_id)
                                ->where('user_id', $proxy->user_id)
                                ->where('type', $proxy->type == 'socks' ? 'http' : 'socks')
                                ->where('autopay', 1)
                                ->where('autopay_days', $proxy->autopay_days)
                                ->get();

                            $count = $proxy2s->count();

                            if ($count == 1) {
                                $proxy2 = $proxy2s->first();
                                $proxy2->date_end = $current;
                                $proxy2->save();
                                $proxy->date_end = $current;
                                $proxy->save();

                                if ($siteSetting->referral_balance_enabled == 1) {
                                    debitingBalance($user->id, $price, 'Продление прокси', 'referral', 1, $monthReq, null, $finalDiscount);
                                } else {
                                    debitingBalance($user->id, $price, 'Продление прокси', null, 1, $monthReq, null, $finalDiscount);
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

                                            $subject = "Прокси успешно продлен!";
                                            $to = $proxy->user->email;

                                            Mail::send('emails.proxy_extend', ['proxy' => $proxy->id, 'proxy2' => $proxy->id + 1, 'user' => $proxy->user], function ($message) use ($subject, $to) {
                                                $message->subject($subject);
                                                $message->to($to);
                                            });
                                        }
                                    }
                                }
                                $subject = "Прокси успешно продлен!";
                                $to = $proxy->user->email;

                                Mail::send('emails.proxy_extend', ['proxy' => $proxy->id, 'proxy2' => $proxy->id + 1, 'user' => $proxy->user], function ($message) use ($subject, $to) {
                                    $message->subject($subject);
                                    $message->to($to);
                                });
                            } else {
                                $proxy2sNew = Proxy::where('date_end', $proxy->date_end)
                                    ->where('modem_id', $proxy->modem_id)
                                    ->where('user_id', $proxy->user_id)
                                    ->where('autopay', 1)
                                    ->where('autopay_days', $proxy->autopay_days)
                                    ->get();

                                foreach ($proxy2sNew as $key2 => $p) {
                                    $p->date_end = $current;
                                    $p->save();
                                    if ($p->type == 'socks') {
                                        if ($siteSetting->referral_balance_enabled == 1) {
                                            debitingBalance($user->id, $price, 'Продление прокси', 'referral', 1, $monthReq, null, $finalDiscount);
                                        } else {
                                            debitingBalance($user->id, $price, 'Продление прокси', null, 1, $monthReq, null, $finalDiscount);
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

                                                    $subject = "Прокси успешно продлен!";
                                                    $to = $proxy->user->email;

                                                    Mail::send('emails.proxy_extend', ['proxy' => $proxy->id, 'proxy2' => $proxy->id + 1, 'user' => $proxy->user], function ($message) use ($subject, $to) {
                                                        $message->subject($subject);
                                                        $message->to($to);
                                                    });
                                                }
                                            }
                                        }
                                        $subject = "Прокси успешно продлен!";
                                        $to = $proxy->user->email;

                                        Mail::send('emails.proxy_extend', ['proxy' => $proxy->id, 'proxy2' => $proxy->id + 1, 'user' => $proxy->user], function ($message) use ($subject, $to) {
                                            $message->subject($subject);
                                            $message->to($to);
                                        });
                                    }
                                }
                            }
                            blockProxy($ipSetting, true, $proxy->type, $auth, false, $proxy->maxconn, $proxy->number_proxy, $apiKey, $proxy->user->name, $proxy->user->id, $proxy->modem->id_kraken, $proxy->id, $proxy->id_kraken, $proxy->date_end);
                        } else {
                            blockProxy($ipSetting, false, $proxy->type, $auth, false, $proxy->maxconn, $proxy->number_proxy, $apiKey, $proxy->user->name, $proxy->user->id, $proxy->modem->id_kraken, $proxy->id, $proxy->id_kraken, $proxy->date_end);
                        }
                    } else {
                        blockProxy($ipSetting, false, $proxy->type, $auth, false, $proxy->maxconn, $proxy->number_proxy, $apiKey, $proxy->user->name, $proxy->user->id, $proxy->modem->id_kraken, $proxy->id, $proxy->id_kraken, $proxy->date_end);
                    }
                } elseif ($totalDuration < 2 and $totalDuration >= 0) {


                    // $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);

                    $server = $proxy->modem->server;
                    $ipSetting = $server->data['url'];
                    $loginSetting = $server->data['login'];
                    $passwordSetting = $server->data['password'];

                    $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);


                    try {
                        //Базовая цена
                        $user = $proxy->user;
                        $settingData = SettingKraken::find(1);
                        $proxyType = $proxy->modem->type_pay;
                        $monthReq = $proxy->autopay_days;
                        $endDateDB = $proxy->date_end;
                        $userProxyCount = count($user->proxys) / 2;

                        $siteSetting = siteSetting::find(1);
                        $balance = $user->balance;
                        $referral_balance = $user->referral_balance;
                        // Раздельный баланс или нет
                        if ($siteSetting->referral_balance_enabled == 1) {
                            $balance = $balance + $referral_balance;
                        }

                        if ($proxy->autopay) {
                            $price = $proxy->price;
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

                            if ($balance >= $price) {
                                $current = Carbon::make($endDateDB)->addDays($monthReq);
                                $proxy2s = Proxy::where('date_end', $proxy->date_end)
                                    ->where('modem_id', $proxy->modem_id)
                                    ->where('user_id', $proxy->user_id)
                                    ->where('type', $proxy->type == 'socks' ? 'http' : 'socks')
                                    ->where('autopay', 1)
                                    ->where('autopay_days', $proxy->autopay_days)
                                    ->get();

                                $count = $proxy2s->count();

                                if ($count == 1) {
                                    $proxy2 = $proxy2s->first();
                                    $proxy2->date_end = $current;
                                    $proxy2->save();
                                    $proxy->date_end = $current;
                                    $proxy->save();
                                    if ($siteSetting->referral_balance_enabled == 1) {
                                        debitingBalance($user->id, $price, 'Продление прокси', 'referral', 1, $monthReq, null, $finalDiscount);
                                    } else {
                                        debitingBalance($user->id, $price, 'Продление прокси', null, 1, $monthReq, null, $finalDiscount);
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

                                                $subject = "Прокси успешно продлен!";
                                                $to = $proxy->user->email;

                                                Mail::send('emails.proxy_extend', ['proxy' => $proxy->id, 'proxy2' => $proxy->id + 1, 'user' => $proxy->user], function ($message) use ($subject, $to) {
                                                    $message->subject($subject);
                                                    $message->to($to);
                                                });
                                            }
                                        }
                                    }
                                    $subject = "Прокси успешно продлен!";
                                    $to = $proxy->user->email;

                                    Mail::send('emails.proxy_extend', ['proxy' => $proxy->id, 'proxy2' => $proxy->id + 1, 'user' => $proxy->user], function ($message) use ($subject, $to) {
                                        $message->subject($subject);
                                        $message->to($to);
                                    });
                                } else {
                                    $proxy2sNew = Proxy::where('date_end', $proxy->date_end)
                                        ->where('modem_id', $proxy->modem_id)
                                        ->where('user_id', $proxy->user_id)
                                        ->where('autopay', 1)
                                        ->where('autopay_days', $proxy->autopay_days)
                                        ->get();

                                    foreach ($proxy2sNew as $key2 => $p) {
                                        $p->date_end = $current;
                                        $p->save();
                                        if ($p->type == 'socks') {
                                            if ($siteSetting->referral_balance_enabled == 1) {
                                                debitingBalance($user->id, $price, 'Продление прокси', 'referral', 1, $monthReq, null, $finalDiscount);
                                            } else {
                                                debitingBalance($user->id, $price, 'Продление прокси', null, 1, $monthReq, null, $finalDiscount);
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

                                                        $subject = "Прокси успешно продлен!";
                                                        $to = $proxy->user->email;

                                                        Mail::send('emails.proxy_extend', ['proxy' => $proxy->id, 'proxy2' => $proxy->id + 1, 'user' => $proxy->user], function ($message) use ($subject, $to) {
                                                            $message->subject($subject);
                                                            $message->to($to);
                                                        });
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $subject = "Прокси успешно продлен!";
                                    $to = $proxy->user->email;

                                    Mail::send('emails.proxy_extend', ['proxy' => $proxy->id, 'proxy2' => $proxy->id + 1, 'user' => $proxy->user], function ($message) use ($subject, $to) {
                                        $message->subject($subject);
                                        $message->to($to);
                                    });
                                }
                                blockProxy($ipSetting, true, $proxy->type, $auth, false, $proxy->maxconn, $proxy->number_proxy, $apiKey, $proxy->user->name, $proxy->user->id, $proxy->modem->id_kraken, $proxy->id, $proxy->id_kraken, $proxy->date_end);
                            } else {
                                blockProxy($ipSetting, false, $proxy->type, $auth, false, $proxy->maxconn, $proxy->number_proxy, $apiKey, $proxy->user->name, $proxy->user->id, $proxy->modem->id_kraken, $proxy->id, $proxy->id_kraken, $proxy->date_end);
                            }
                        } else {
                            blockProxy($ipSetting, false, $proxy->type, $auth, false, $proxy->maxconn, $proxy->number_proxy, $apiKey, $proxy->user->name, $proxy->user->id, $proxy->modem->id_kraken, $proxy->id, $proxy->id_kraken, $proxy->date_end);

                            // Если происходит ошибка, то выбрасывается исключение
                            // throw new Exception("Произошла ошибка!");
                        }
                    } catch (Exception $e) {
                        // Обработка исключения

                        $errorMessage = "Исключение: " . $e->getMessage();

                        // Запись ошибки в файл
                        $errorLogFile = 'error_log.txt';
                        file_put_contents($errorLogFile, $errorMessage, FILE_APPEND);
                    }

                    $subject = "Пожалуйста, обратите внимание, что доступ к вашим прокси будет заблокирован. Продлите услугу";



                    if ($chatId and $settingNotices->telegram_check == 1) {
                        $telegram = new Api($settingNotices->telegram_token);
                        // Отправляем сообщение с chat_id пользователю
                        $telegram->sendMessage([
                            'chat_id' => $chatId,
                            'text' => $subject
                        ]);
                    }
                }
            }
        }

        /*

           Схема вашей базы данных

           Item::create(['name'=>'hello new']);

        */
        $this->info('Прокси проверены и предприняты действия!');
    }
}
