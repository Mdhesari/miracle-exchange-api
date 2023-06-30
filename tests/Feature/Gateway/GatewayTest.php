<?php

use Modules\Gateway\Entities\Gateway;

beforeEach(fn() => $this->actingAs() && givePerm('gateways'));

it('can admin create gateway', function () {
    $response = $this->post(route('gateways.store'), [
        'title'          => 'md',
        'sheba_number'   => '123',
        'account_number' => '123',
        'account_name'   => 'mohamad',
        'is_active'      => true,
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

it('cannot user create gateway', function () {
    $this->actingAs();

    $response = $this->post(route('gateways.store'), [
        'title'          => 'md',
        'sheba_number'   => '123',
        'account_number' => '123',
        'account_name'   => 'mohamad',
        'is_active'      => true,
    ]);

    $response->assertForbidden();
});

it('can admin update gateway', function () {
    $gateway = Gateway::factory()->create();

    $response = $this->put(route('gateways.update', $gateway), [
        'title'          => 'md',
        'sheba_number'   => '123',
        'account_number' => '123',
        'account_name'   => 'mohamad',
        'is_active'      => true,
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

it('can get gateway', function () {
    $gateway = Gateway::factory()->create();

    $response = $this->get(route('gateways.show', $gateway));

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'title'          => $gateway->title,
                'sheba_number'   => $gateway->sheba_number,
                'account_number' => $gateway->account_number,
                'account_name'   => $gateway->account_name,
            ],
        ],
    ]);
});

it('can user get gateways', function () {
    $this->actingAs();

    $gateway = Gateway::factory()->create();

    $response = $this->get(route('gateways.index'));

    $response->assertSuccessful()->assertJson([
        'data' => [
            'items' => [
                'data' => [
                    [
                        'title'          => $gateway->title,
                        'sheba_number'   => $gateway->sheba_number,
                        'account_number' => $gateway->account_number,
                        'account_name'   => $gateway->account_name,
                    ],
                ],
            ],
        ],
    ]);
});

it('can delete gateway', function () {
    $gateway = Gateway::factory()->create();

    $response = $this->delete(route('gateways.destroy', $gateway));

    $response->assertSuccessful();

    $this->isSoftDeletableModel($gateway);
});

