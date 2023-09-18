<?php

namespace Modules\Bank\Actions;

use Modules\Bank\Entities\Bank;
use Modules\Bank\Events\BankUpdated;

class DeleteBank
{
    public function __invoke(Bank $model)
    {
        $model->delete();

        // other relations to be deleted...
    }
}
