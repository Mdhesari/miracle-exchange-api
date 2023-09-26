<?php

namespace Modules\Auth\Actions;

use App\Models\User;

class SetupToken
{
    public function __invoke(User $user, array $data = [])
    {
        return auth()->login($user);
    }
}
