<?php

namespace Modules\Market\Actions;

use Modules\Market\Entities\Market;

class CreateMarket
{
    public function __invoke(array $data)
    {
        return Market::create($data);
    }
}
