<?php

namespace Modules\Account\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Account\Entities\Account;

class AccountPolicy
{
    public function update(User $user, Account $account)
    {
        return $user->isOwner($account->user_id) || $user->can('accounts');
    }

    public function show(User $user, Account $account)
    {
        return $user->isOwner($account->user_id) || $user->can('accounts');
    }

    public function destroy(User $user, Account $account)
    {
        return $user->isOwner($account->user_id) || $user->can('accounts');
    }
}
