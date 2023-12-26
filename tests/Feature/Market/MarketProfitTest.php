<?php

use Modules\Market\Entities\Market;

beforeEach(fn() => $this->actingAs());

it('can update market profit price', function () {
    givePerm('markets');

    $m = Market::factory()->create([
        'price' => 12000,
    ]);

    $response = $this->put(route('markets.update', $m), [
        'profit_price' => 3000,
    ]);

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'profit_price' => 3000,
                'total_price'  => 15000,
            ]
        ]
    ]);
});
