<?php

namespace Modules\RolePermission\Actions;

use Modules\RolePermission\Entities\Role;

class CreateRole
{
    public function __invoke(array $data)
    {
        $role = Role::create([
            'name'       => $data['name'],
            'guard_name' => 'sanctum',
        ]);

        if ( isset($data['permissions']) ) {
            $role->syncPermissions($data['permissions']);
        }

        if ( isset($data['users']) ) {
            $role->users()->sync($data['users']);
        }

        return $role->fresh();
    }
}
