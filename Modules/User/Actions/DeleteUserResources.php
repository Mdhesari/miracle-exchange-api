<?php

namespace Modules\User\Actions;

use App\Models\User;

class DeleteUserResources
{
    public function __invoke(User $user)
    {
        $user->roles()->sync([]);
        $user->permissions()->sync([]);
    }
}
