<?php

namespace Ssda1\proxies\Console\Commands;

use Illuminate\Console\Command;

class CustomSeedCommand extends Command
{
    protected $signature = 'package:seed';
    protected $description = 'Seed the package database with custom seeders';

    public function handle()
    {
        $this->info('Seeding package database...');

        $seeders = [
            'Ssda1\\proxies\\Database\\Seeders\\PermissionTableSeeder',
            'Ssda1\\proxies\\Database\\Seeders\\AdminRoleSeeder',
        ];

        foreach ($seeders as $seeder) {
            $this->call('db:seed', ['--class' => $seeder]);
            $this->info('Seeder ' . $seeder . ' completed.');
        }

        $this->info('Seeding complete.');
    }
}
