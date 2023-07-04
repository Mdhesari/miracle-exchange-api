<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'tickets',
            'transactions',
            'markets',
            'orders',
            'gateways',
            'users',
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
