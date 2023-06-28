<?php

namespace Modules\User\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    public function update(User $authUser, User $user)
    {
        return $authUser->isOwner($user->id) || $authUser->can('users');
    }

    public function destroy(User $authUser, User $user)
    {
        return $authUser->isOwner($user->id) || $authUser->can('users');
    }
}
