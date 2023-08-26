<?php

namespace Modules\Order\Listeners;

use Modules\Auth\Jobs\SendSMS;
use Modules\Order\Events\OrderCreated;

class SendOrderNotifications
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param OrderCreated $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        SendSMS::dispatch($order->user->mobile, 'submitOrder', [
            // amount
            $order->cumulative_quote_quantity,
            // currency
            $order->market->persian_name,
            // currency price
            $order->executed_price,
        ]);
    }
}
