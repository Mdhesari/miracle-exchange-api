<?php

namespace Modules\Gateway\Actions;

use Modules\Gateway\Entities\Gateway;

class CreateGateway
{
    public function __invoke(array $data)
    {
        return Gateway::create($data);
    }
}
