<?php

namespace Modules\RolePermission\Actions;

use Modules\RolePermission\Entities\Role;
use Modules\RolePermission\RolePermission;

class RevokeRoleFromUsers
{
    public function __invoke(Role $role, array $data)
    {
        foreach (RolePermission::getUserModel()->whereIn('id', $data['users'])->cursor() as $user) {
            $user->removeRole($role);
        }
    }
}
