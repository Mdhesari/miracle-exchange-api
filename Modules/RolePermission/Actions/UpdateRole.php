<?php

namespace Modules\RolePermission\Actions;

use Modules\RolePermission\Entities\Role;

class UpdateRole
{
    public function __invoke(Role $role, array $data)
    {
        if ( isset($data['name']) ) {
            $role->updateOrFail([
                'name' => $data['name'],
            ]);
        }

        if ( isset($data['permissions']) ) {
            $role->syncPermissions($data['permissions']);
        }

        if ( isset($data['users']) ) {
            $role->users()->sync($data['users']);
        }

        return $role->fresh();
    }
}
