<?php

namespace Modules\Order\Actions;

use Modules\Market\Entities\Market;
use Modules\Order\Entities\Order;

class UpdateOrder
{
    public function __construct(
        private PrepareOrderData $prepareOrderData
    )
    {
        //
    }

    public function __invoke(Order $order, array $data)
    {
        $data = ($this->prepareOrderData)($data);

        $order->update($data);

        return $order;
    }
}
