<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Modules\Order\Entities\Order;
use Modules\Order\Events\AdminOrderTransactionCreated;
use Modules\Revenue\Entities\Revenue;
use Modules\Wallet\Entities\Transaction;
use Modules\Wallet\Events\TransactionReferenceUpdated;

beforeEach(fn() => $this->actingAs());

it('can verify a transaction and revenue is created for the inviter', function () {
    givePerm('transactions');

    Event::fake([TransactionReferenceUpdated::class, AdminOrderTransactionCreated::class]);

    Auth::user()->update([
        'inviter_id'          => ($user = User::factory()->create())->id,
        'inviter_has_revenue' => false,
    ]);

    Auth::user()->transactions()->delete();

    $transaction = Transaction::factory()->create([
        'status'  => Transaction::STATUS_PENDING,
        'type'    => Transaction::TYPE_WITHDRAW,
        'user_id' => Auth::id(),
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

    $this->assertEquals(1, Revenue::where('user_id', $user->id)->count());
});
