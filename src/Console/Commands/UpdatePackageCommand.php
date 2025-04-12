<?php

namespace Ssda1\proxies\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class UpdatePackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proxies:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновить пакет ssda-1/proxies';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Начало обновления пакета ssda-1/proxies...');
        
        try {
            // Используем Composer API через PHP
            $composerPath = base_path('composer.phar');
            
            if (file_exists($composerPath)) {
                // Если есть локальный composer.phar
                $command = 'php ' . $composerPath . ' update ssda-1/proxies --no-interaction';
            } else {
                // Иначе используем глобальный composer
                $command = 'composer update ssda-1/proxies --no-interaction';
            }
            
            $process = Process::fromShellCommandline($command, base_path());
            $process->setTimeout(300); // 5 минут таймаут
            
            $process->run(function ($type, $buffer) {
                $this->line($buffer);
            });
            
            $this->info('Обновление пакета ssda-1/proxies завершено.');
            return 0;
        } catch (\Exception $e) {
            $this->error('Ошибка при обновлении пакета: ' . $e->getMessage());
            return 1;
        }
    }
}
