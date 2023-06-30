<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Modules\Gateway\Entities\Gateway;
use Modules\Market\Entities\Market;
use Modules\Order\Actions\CreateOrderTransaction;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Events\OrderTransactionCreated;
use Modules\Wallet\Entities\Transaction;
use Modules\Wallet\Events\TransactionReferenceUpdated;
use function Pest\Laravel\post;

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
    Event::fake([OrderTransactionCreated::class]);

    $gateway = Gateway::factory()->create();

    $order = Order::factory()->create([
        'user_id' => Auth::id(),
    ]);

    $response = $this->post(route('orders.payment', $order), [
        'gateway_id' => $gateway->id,
    ]);

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'gateway_id'      => 1,
                'quantity'        => $order->cumulative_quote_quantity,
                'status'          => Transaction::STATUS_GATEWAY_PENDING,
                'transactionable' => [
                    'status' => OrderStatus::Pending->name
                ]
            ]
        ]
    ]);

    Event::assertDispatched(OrderTransactionCreated::class);
});

it('can update transaction reference and new order status', function () {
    Event::fake([TransactionReferenceUpdated::class]);

    $order = Order::factory()->create();

    $transaction = app(CreateOrderTransaction::class)($order, [
        'gateway_id' => Gateway::factory()->create()->id,
    ]);

    $response = $this->put(route('transactions.reference', [
        'transaction' => $transaction,
        'expand'      => 'transactionable',
    ]), [
        'reference' => 'test',
    ]);

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'status'          => Transaction::STATUS_ADMIN_PENDING,
                'transactionable' => [
                    'status' => OrderStatus::AdminPending->name,
                ]
            ]
        ]
    ]);

    Event::assertDispatched(TransactionReferenceUpdated::class);
});
