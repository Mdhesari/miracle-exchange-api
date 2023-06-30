<?php

namespace Modules\Gateway\Actions;

use Modules\Gateway\Entities\Gateway;

class UpdateGateway
{
    public function __invoke(Gateway $gateway, array $data)
    {
        $gateway->update($data);

        return $gateway;
    }
}
