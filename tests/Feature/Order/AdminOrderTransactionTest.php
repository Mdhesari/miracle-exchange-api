<?php

use Illuminate\Support\Facades\Event;
use Modules\Gateway\Entities\Gateway;
use Modules\Order\Actions\CreateOrderTransaction;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Events\AdminOrderTransactionCreated;
use Modules\Wallet\Entities\Transaction;

beforeEach(fn() => $this->actingAs());

it('can admin create order transaction', function () {
    Event::fake([AdminOrderTransactionCreated::class]);

    givePerm('orders');

    $order = Order::factory()->create();

    $response = $this->post(route('orders.admin-payment', $order), [
        'reference' => 'test',
    ]);

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'status'          => Transaction::STATUS_VERIFIED,
                'transactionable' => [
                    'status' => OrderStatus::Done->name,
                ]
            ]
        ]
    ]);

    Event::assertDispatched(AdminOrderTransactionCreated::class);
});
