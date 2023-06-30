<?php

namespace Modules\Account\Actions;

use Modules\Account\Entities\Account;

class UpdateAccount
{
    public function __invoke(Account $account, array $data)
    {
        $account->update($data);

        return $account;
    }
}
