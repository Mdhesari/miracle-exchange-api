<?php

namespace Modules\Bank\Actions;

use Modules\Bank\Entities\Bank;
use Modules\Bank\Events\BankUpdated;

class UpdateBank
{
    public function __invoke(Bank $model ,array $data)
    {
        $model->update($data);

        event(new BankUpdated($model));

        return $model;
    }
}
