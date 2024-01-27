<?php

namespace Modules\Order\Listeners;

use App\Models\User;
use Illuminate\Support\Str;
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

        if ($order->user->mobile) {
            SendSMS::dispatch($order->user->mobile, 'submitOrder', [
                // price
                $order->fromatted_cumulative_quote_quantity.'_تومان',
                // currency
                Str::replace(' ', '_', $order->market->persian_name),
                // quantity
                $order->formatted_executed_quantity,
            ]);
        }

        foreach (User::permission('order')->whereNotNull('mobile')->cursor() as $user) {
            SendSMS::dispatch($user->mobile, 'adminSubmitOrder', [
                // currency
                Str::replace(' ', '_', $order->market->persian_name),
                // quantity
                $order->formatted_executed_quantity,
                // price
                $order->fromatted_cumulative_quote_quantity.'_تومان',
            ]);
        }
    }
}
