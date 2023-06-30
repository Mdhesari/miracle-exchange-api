<?php

namespace Modules\Order\Observers;

use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatus;

class OrderObserver
{
    public function creating(Order $order)
    {
        if ( ! $order->fill_percentage ) {
            $order->fill_percentage = 0;
        }

        if ( ! $order->status ) {
            $order->status = OrderStatus::Pending->name;
        }
    }
}
