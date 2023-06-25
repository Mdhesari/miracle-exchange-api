<?php

namespace Modules\RolePermission\Actions;

use Illuminate\Validation\ValidationException;
use Modules\RolePermission\Entities\Role;

class DeleteRole
{
    /**
     * @throws \Throwable
     * @throws ValidationException
     */
    public function __invoke(Role $role)
    {
        if ( $role->users()->count() > 0 ) {
            throw ValidationException::withMessages([
                'role' => __('validation.role-conflict', [
                    'attribute' => 'role',
                ]),
            ]);
        }

        $role->deleteOrFail();
    }
}
