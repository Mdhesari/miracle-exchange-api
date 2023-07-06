<?php

namespace App\Observers\User;

use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        cache()->forget('users::count');
    }
}
