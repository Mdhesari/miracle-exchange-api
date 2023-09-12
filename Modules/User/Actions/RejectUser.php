<?php

namespace Modules\User\Actions;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\User\Events\UserRejected;

class RejectUser
{
    public function __invoke(User $user)
    {
        $user->update([
            'status' => UserStatus::Rejected->name,
            'meta'   => array_merge($user->meta ?? [], [
                'admin_id' => Auth::user()->id,
            ])
        ]);

        event(new UserRejected($user));
    }
}
