<?php

namespace Modules\RolePermission\Actions;

use Modules\RolePermission\Entities\Role;
use Modules\RolePermission\RolePermission;

class AssignRoleToUsers
{
    public function __invoke(Role $role, array $data)
    {
        foreach (RolePermission::getUserModel()->whereIn('id', $data['users'])->get() as $user) {
            $user->assignRole($role);
        }
    }
}
