<?php

namespace Modules\Market\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Market\Entities\Market;

class MarketPolicy
{
    public function show(User $user, Market $market)
    {
        return $market->isEnabled() || $user->can('markets');
    }
}
