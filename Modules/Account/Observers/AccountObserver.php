<?php

namespace Modules\Account\Observers;

use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\Account;

class AccountObserver
{
    public function creating(Account $account)
    {
        if ( ! $account->user_id ) {
            $account->user_id = Auth::id();
        }
    }
}
