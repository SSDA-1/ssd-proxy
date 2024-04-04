<?php

namespace ssda1\proxies\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class SetWebhookCommand extends Command
{
    protected $name = 'start';
    protected $signature = 'telegram:set-webhook';
    protected $description = 'Set Telegram bot webhook';

    public function handle(): void
    {
        $url = url('/telegram/webhook');

        try {
            Telegram::setWebhook(['url' => $url]);
            $this->info('Webhook has been set successfully!');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
