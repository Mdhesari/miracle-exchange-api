<?php

use Modules\Market\Entities\Market;
use Modules\Market\Enums\MarketStatus;
use function Pest\Laravel\assertModelMissing;

beforeEach(fn() => $this->actingAs());

it('can create a new market', function () {
    givePerm('markets');

    $response = $this->post(route('markets.store'), [
        'symbol' => 'usd',
        'price'  => 50000,
    ]);

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'symbol' => 'usd',
            ]
        ]
    ]);
});

it('can update a new market', function () {
    $market = Market::factory()->create();

    givePerm('markets');

    $response = $this->put(route('markets.update', $market), [
        'symbol' => 'usd',
        'price'  => 50000,
    ]);

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'symbol' => 'usd',
                'price'  => '50000',
            ]
        ]
    ]);
});

it('can delete a market', function () {
    $market = Market::factory()->create();

    givePerm('markets');

    $response = $this->delete(route('markets.destroy', $market));

    $response->assertSuccessful();

    assertModelMissing($market);
});

it('can get markets', function () {
    Market::factory()->create();

    $response = $this->get(route('markets.index'));

    $response->assertSuccessful()->assertJsonStructure([
        'data' => [
            'items' => [
                'data' => [
                    [
                        'name', 'symbol', 'price',
                    ]
                ]
            ]
        ]
    ]);
});

it('can get a market', function () {
    $market = Market::factory()->create([
        'symbol' => 'md_buy',
        'price'  => 30000,
        'status' => MarketStatus::Enabled->name,
    ]);

    $response = $this->get(route('markets.show', $market));

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'symbol' => 'md_buy',
                'price'  => "30000",
            ]
        ]
    ]);
});

it('cannot get a disabled market', function () {
    $market = Market::factory()->create([
        'symbol' => 'md_buy',
        'price'  => 30000,
        'status' => MarketStatus::Disabled->name,
    ]);

    $response = $this->get(route('markets.show', $market));

    $response->assertSuccessful();
});

it('can toggle market status', function () {
    givePerm('markets');

    $market = Market::factory()->create([
        'status' => MarketStatus::Disabled->name,
    ]);

    $response = $this->put(route('markets.status-toggle', $market));

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'status' => MarketStatus::Enabled->name,
            ]
        ]
    ]);
});
