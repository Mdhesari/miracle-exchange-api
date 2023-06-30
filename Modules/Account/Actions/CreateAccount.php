<?php

namespace Modules\Account\Actions;

use Modules\Account\Entities\Account;

class CreateAccount
{
    public function __invoke(array $data)
    {
        return Account::create($data);
    }
}
