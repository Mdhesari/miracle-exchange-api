<?php

use Illuminate\Support\Facades\Auth;
use Modules\Market\Entities\Market;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatus;
use Modules\Wallet\Entities\Transaction;

beforeEach(fn() => $this->actingAs());

it('can setup order', function () {
    $response = $this->post(route('orders.store'), [
        'cumulative_quote_quantity' => 20000000,
        'market_id'                 => ($market = Market::factory()->create())->id,
    ]);

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'executed_price'    => $market->price,
                'executed_quantity' => number_format(20000000 / $market->price, 2, null, null),
            ]
        ]
    ]);
});

it('can get transaction and choose payment method', function () {
    $order = Order::factory()->create([
        'user_id' => Auth::id(),
    ]);

    $response = $this->post(route('orders.payment'), [
        'gateway_id' => 1,
    ]);

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'gateway_id'   => 1,
                'quantity'     => $order->cumulative_quote_quantity,
                'status'       => Transaction::STATUS_ADMIN_PENDING,
                'pivot_status' => OrderStatus::AdminPending,
            ]
        ]
    ]);
});
