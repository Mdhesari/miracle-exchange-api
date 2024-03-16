<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'statistics',
            'tickets',
            'transactions',
            'markets',
            'orders',
            'gateways',
            'users',
            'accounts',
            'revenues',
            'landing',
            'telegram',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate([
                'name'       => $name,
                'guard_name' => 'api',
            ]);
        }

        Role::firstOrCreate([
            'name'       => 'super-admin',
            'guard_name' => 'api',
        ])->permissions()->sync(Permission::pluck('id'));
    }
}
