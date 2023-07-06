<?php

namespace App\Observers\Order;

use Modules\Order\Entities\Order;

class OrderObserver
{
    public function created(Order $order)
    {
        cache()->forget('orders::count');
    }
}
