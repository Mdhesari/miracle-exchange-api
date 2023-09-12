<?php

namespace Modules\User\Actions;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthorizeUser
{
    public function __invoke(User $user,)
    {
        $user->update([
            'status' => UserStatus::Accepted->name,
            'meta'   => array_merge($user->meta ?? [], [
                'admin_id' => Auth::user()->id,
            ])
        ]);
    }
}
