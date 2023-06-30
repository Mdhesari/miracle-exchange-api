<?php

namespace Modules\Order\Observers;

use Illuminate\Support\Facades\Auth;
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

        if ( ! $order->user_id ) {
            $order->user_id = Auth::id();
        }
    }
}
