<?php

namespace ssda1\proxies\Http\Controllers;

use ssda1\proxies\Models\SettingNotices;
use ssda1\proxies\Models\Notice;
use ssda1\proxies\Models\User;
use ssda1\proxies\Notifications\Telegram;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Foundation\Application;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

use NotificationChannels\Telegram\TelegramUpdates;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

class TelegramController extends Controller
{
    /**
     * Отобразить форму для создания нового ресурса.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);
        // $user = Auth::user();

        if ($user->telegram_id != NULL) {
            $notice = new Notice([
                'notice_title' => 'Привет ' . $user->name,
                'notice_description' => $user->email . "\n" . $user->name,
                'link_name' => 'Тест кнопки',
                'notice_button' => 'https://sandbox.ssdd.gq',
                'telegram_id' => $user->telegram_id,
            ]);

            Notification::send($notice, new Telegram());
        }
        return 1;
    }

    /**
     * Обработка входящей команды от Telegram.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function webhook(Request $request)
    {

        $data = 'Ваш текст данных';

        $filePath = public_path('filename.txt');

        // Открываем файл в режиме записи
        $file = fopen($filePath, 'w');

        // Записываем данные в файл
        fwrite($file, $data);

        // Закрываем файл
        fclose($file);


        $settingNotices = SettingNotices::find(1);
        // Создаем экземпляр класса Api, передав в него токен API
        $telegram = new Api($settingNotices->telegram_token);
        // $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));






        // Получаем chat_id пользователя
        $chatId = $request->message['from']['id'];

        // Отправляем сообщение с chat_id пользователю
        $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => "Ваш chat_id: " . $chatId
        ]);

        return 1;
    }
}
