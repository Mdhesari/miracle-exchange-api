<?php

namespace Modules\Actions;

use Modules\Revenue\Entities\Revenue;
use Modules\Revenue\Events\RevenueCreated;

class CreateRevenue
{
    public function __invoke(array $data)
    {
        $revenue = Revenue::create($data);

        event(new RevenueCreated($revenue));

        return $revenue;
    }
}
