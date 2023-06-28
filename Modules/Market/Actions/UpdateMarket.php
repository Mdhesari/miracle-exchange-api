<?php

namespace Modules\Market\Actions;

use Modules\Market\Entities\Market;

class UpdateMarket
{
    public function __invoke(Market $market, array $data)
    {
        $market->update($data);

        return $market;
    }
}
