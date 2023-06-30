<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\Account;

beforeEach(fn() => $this->actingAs());

it('can user create account', function () {
    $response = $this->post(route('accounts.store'), [
        'title'          => 'md',
        'sheba_number'   => '123',
        'account_number' => '123',
        'account_name'   => 'mohamad',
    ]);

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'title'          => 'md',
                'sheba_number'   => '123',
                'account_number' => '123',
                'account_name'   => 'mohamad',
            ]
        ]
    ]);
});

it('can user update account', function () {
    $account = Account::factory()->create([
        'user_id' => Auth::id(),
    ]);

    $response = $this->put(route('accounts.update', $account), [
        'title'          => 'md',
        'sheba_number'   => '123',
        'account_number' => '123',
        'account_name'   => 'mohamad',
    ]);

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'title'          => 'md',
                'sheba_number'   => '123',
                'account_number' => '123',
                'account_name'   => 'mohamad',
            ]
        ]
    ]);
});

it('cannot user update others account', function () {
    $account = Account::factory()->create([
        'user_id'   =>  User::factory()->create()->id,
    ]);

    $response = $this->put(route('accounts.update', $account), [
        'title'          => 'md',
        'sheba_number'   => '123',
        'account_number' => '123',
        'account_name'   => 'mohamad',
    ]);

    $response->assertForbidden();
});

it('can get account', function () {
    $account = Account::factory()->create([
        'user_id' => Auth::id(),
    ]);

    $response = $this->get(route('accounts.show', $account));

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'title'          => $account->title,
                'sheba_number'   => $account->sheba_number,
                'account_number' => $account->account_number,
                'account_name'   => $account->account_name,
            ],
        ],
    ]);
});

it('can user get accounts', function () {
    $this->actingAs();

    $account = Account::factory()->create();

    $response = $this->get(route('accounts.index'));

    $response->assertSuccessful()->assertJson([
        'data' => [
            'items' => [
                'data' => [
                    [
                        'title'          => $account->title,
                        'sheba_number'   => $account->sheba_number,
                        'account_number' => $account->account_number,
                        'account_name'   => $account->account_name,
                    ],
                ],
            ],
        ],
    ]);
});

it('can delete account', function () {
    $account = Account::factory()->create();

    $response = $this->delete(route('accounts.destroy', $account));

    $response->assertSuccessful();

    $this->isSoftDeletableModel($account);
});

