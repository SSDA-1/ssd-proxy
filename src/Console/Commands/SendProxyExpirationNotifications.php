<?php

namespace Ssda1\proxies\Console\Commands;

use Ssda1\proxies\Models\Proxy;
use Ssda1\proxies\Models\ProcessLog;
use Ssda1\proxies\Models\SettingNotices;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use NotificationChannels\Telegram\TelegramUpdates;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

class SendProxyExpirationNotifications extends Command
{
    protected $signature = 'proxies:send-emails';

    protected $description = 'Sends emails to notify users of expiring proxies';

    public function handle()
    {
        $settingNotices = SettingNotices::find(1);
        $proxies = Proxy::where('date_end', '>=', Carbon::now())
            ->where('date_end', '<=', Carbon::now()->addDays(3))
            ->get();

        $proxiesByUser = $proxies->groupBy('user_id');

        foreach ($proxiesByUser as $userId => $userProxies) {
            $proxy = $userProxies[0];
            $proxy2 = $userProxies[1];
            $days_left = Carbon::now()->diffInDays($proxy->date_end);
            $subject = "Ваша аренда прокси скоро истечет. Осталось $days_left дней!";
            $to = $proxy->user->email;
            $chatId = isset($proxy->user->telegram_chat_id) ? $proxy->user->telegram_chat_id : null;

            try {
                Mail::send('emails.proxy_expiration', ['proxy' => $proxy, 'proxy2' => $proxy2, 'days_left' => $days_left], function ($message) use ($subject, $to) {
                    $message->subject($subject);
                    $message->to($to);
                });
            } catch (\Exception $exception) {
                ProcessLog::create([
                    'name' => 'Отправка mail',
                    'description' => $exception->getMessage()
                ]);

                file_put_contents('ErrorSendMail.txt', $exception->getMessage());
            }

            try {
                if ($chatId && $settingNotices->telegram_check == 1) {
                    $telegram = new Api($settingNotices->telegram_token);

                    $telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => "Ваша аренда прокси скоро истечет!\nОсталось $days_left дней!"
                    ]);
                }
            } catch (\Exception $exception) {
                ProcessLog::create([
                    'name' => 'Отправка в телеграмм',
                    'description' => $exception->getMessage()
                ]);
            }
        }

        $this->info('Emails sent successfully!');
    }
}
