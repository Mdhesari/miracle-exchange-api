<?php

use Modules\Market\Entities\Market;

it('can create market comments', function () {
    $this->actingAs();

    $market = Market::factory()->create();
    $response = $this->post(route('comments.store', [
        'type' => 'markets',
    ]), [
        'title'          => $this->faker->sentence(),
        'comment'        => $this->faker->text(),
        'commentable_id' => $market->id,
    ]);
    $response->dump()->assertSuccessful();
});
