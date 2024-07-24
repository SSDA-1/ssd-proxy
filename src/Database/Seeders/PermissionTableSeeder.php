<?php

namespace Ssda1\proxies\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'news-list',
            'news-create',
            'news-edit',
            'news-delete',
            'admin-panel',
            'menu-list',
            'menu-create',
            'menu-edit',
            'menu-delete',
            'reviews-list',
            'reviews-create',
            'reviews-edit',
            'reviews-delete',
            'faq-list',
            'faq-create',
            'faq-edit',
            'faq-delete',
            'support-list',
            'support-edit',
            'support-delete',
            'advantags-list',
            'advantags-create',
            'advantags-edit',
            'advantags-delete',
            'user-delete',
            'logs-list',
            'promocode-list',
            'countdaysdiscount-list',
            'countproxydiscount-list',
            'tariffsettings-list',
            'countpairsproxydiscount-list',
            'partners-list',
            'partners-create',
            'partners-edit',
            'partners-delete',
        ];

        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web']
            );

            // Ensure guard name is always 'web'
            if ($perm->guard_name != 'web') {
                $perm->guard_name = 'web';
                $perm->save();
            }
        }
    }
}
