<?php

namespace Modules\Order\Actions;

use Modules\Order\Entities\Order;

class UpdateOrder
{
    public function __invoke(Order $order, array $data)
    {
        $order->update($data);

        return $order;
    }
}
