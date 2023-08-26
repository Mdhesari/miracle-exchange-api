<?php

namespace Modules\Order\Actions;

use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;
use Modules\Order\Events\OrderCreated;

class CreateOrder
{
    public function __construct(
        private PrepareOrderData $prepareOrderData
    )
    {
        //
    }

    public function __invoke(array $data)
    {
        $data = ($this->prepareOrderData)($data);

        if (! isset($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }

        $order = Order::create($data);

        event(new OrderCreated($order));

        return $order;

    }
}
