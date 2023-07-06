<?php

beforeEach(fn() => $this->actingAs());

it('can admin get statistics', function () {
    givePerm('statistics');

    $response = $this->get(route('statistics'));

    $response->assertSuccessful()->assertJsonStructure([
        'data' => [
            'items' => [
                'orders_count',
                'users_count',
                'transferred_qua',
            ]
        ]
    ]);
});
