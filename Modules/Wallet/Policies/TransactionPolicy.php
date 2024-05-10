<?php

namespace Modules\Wallet\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Wallet\Entities\Transaction;

class TransactionPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Transaction $transaction): bool
    {
        return $user->isOwner($transaction->user_id) || $user->can('transactions');
    }

    public function payment(User $user, Transaction $transaction): bool
    {
        return $user->isOwner($transaction->user_id) || $user->can('transactions');
    }

    public function verify(User $user, Transaction $transaction): bool
    {
        return $user->can('transactions') && ! $transaction->isRejected();
    }

    public function reject(User $user, Transaction $transaction): bool
    {
        return $user->can('transactions') && ! $transaction->isVerified();
    }

    public function reference(User $user, Transaction $transaction): bool
    {
        return $user->isOwner($transaction->user_id) && ! $transaction->isVerified();
    }
}
