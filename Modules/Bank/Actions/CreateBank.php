<?php

namespace Modules\Bank\Actions;

use Modules\Bank\Entities\Bank;
use Modules\Bank\Events\BankCreated;

class CreateBank
{
    public function __invoke(array $data)
    {
        $model = Bank::create($data);

        event(new BankCreated($model));

        return $model;
    }
}
