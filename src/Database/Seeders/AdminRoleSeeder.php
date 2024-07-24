<?php

namespace Ssda1\proxies\Database\Seeders;

use Illuminate\Database\Seeder;
use Ssda1\proxies\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Update existing permissions to use guard 'web'
        Permission::all()->each(function ($permission) {
            if ($permission->guard_name != 'web') {
                $permission->guard_name = 'web';
                $permission->save();
            }
        });

        // Update existing roles to use guard 'web'
        Role::all()->each(function ($role) {
            if ($role->guard_name != 'web') {
                $role->guard_name = 'web';
                $role->save();
            }
        });

        // Check if user already exists
        $user = User::where('email', 'admin@admin.com')->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('123123Q!')
            ]);
        }

        // Check if role already exists
        $role = Role::where('name', 'Admin')->where('guard_name', 'web')->first();
        if (!$role) {
            $role = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        }

        // Get all permissions
        $permissions = Permission::where('guard_name', 'web')->pluck('id', 'id')->all();

        // Sync permissions only if role does not have them
        if (!$role->hasAllPermissions($permissions)) {
            $role->syncPermissions($permissions);
        }

        // Assign role to user if not already assigned
        if (!$user->hasRole('Admin', 'web')) {
            $user->assignRole('Admin', 'web');
        }
    }
}
