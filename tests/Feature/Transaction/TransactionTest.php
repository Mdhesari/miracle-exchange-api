<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Modules\Order\Entities\Order;
use Modules\Wallet\Entities\Transaction;

beforeEach(fn() => $this->actingAs());

it('can verify a transaction with reference media', function () {
    givePerm('transactions');

    Event::fake();

    $transaction = Transaction::factory()->create([
        'status' => Transaction::STATUS_PENDING,
        'type'   => Transaction::TYPE_WITHDRAW,
    ]);

    $transaction->update([
        'transactionable_id'   => Order::factory()->create([
            'user_id' => 1,
        ])->id,
        'transactionable_type' => Order::class,
    ]);

    $response = $this->put(route('transactions.verify', [
        'expand'      => 'media',
        'transaction' => $transaction
    ]), [
        'reference' => Str::random(),
        'media'     => [
            UploadedFile::fake()->image('test.jpg'),
        ]
    ]);


    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'media' => [
                    0 => [
                        'collection_name' => Transaction::MEDIA_REFERENCE,
                    ]
                ]
            ]
        ]
    ]);
});

it('can update reference of a transaction', function () {
    Event::fake();

    $transaction = Transaction::factory()->create([
        'user_id' => Auth::id(),
        'type'    => Transaction::TYPE_WITHDRAW,
        'status'  => Transaction::STATUS_PENDING,
    ]);

    $response = $this->put(route('transactions.reference', $transaction), [
        'reference' => '123456',
    ]);

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'reference' => '123456',
                'status'    => Transaction::STATUS_ADMIN_PENDING,
            ]
        ]
    ]);
});

it('cannot invalid user update reference of a transaction', function () {
    Event::fake();

    $transaction = Transaction::factory()->create([
        'user_id' => User::factory()->create()->id,
        'type'    => Transaction::TYPE_WITHDRAW,
        'status'  => Transaction::STATUS_PENDING,
    ]);

    $response = $this->put(route('transactions.reference', $transaction), [
        'reference' => '123456',
    ]);

    $response->assertForbidden();
});
