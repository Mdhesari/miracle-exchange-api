<?php

use Illuminate\Support\Facades\Auth;
use Modules\Comment\Entities\Comment;
use Modules\Market\Entities\Market;

it('can create market comment.', function () {
    $this->actingAs();

    $market = Market::factory()->create();
    $response = $this->post(route('comments.store', [
        'type' => 'markets',
    ]), [
        'title'          => $t = $this->faker->sentence(),
        'comment'        => $cm = $this->faker->text(),
        'commentable_id' => $market->id,
    ]);
    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'title'   => $t,
                'comment' => $cm,
            ]
        ]
    ]);
});

it('cannot delete market comment of another user.', function () {
    $this->actingAs();

    $market = Market::factory()->create();
    $response = $this->delete(route('comments.destroy', [
        'type'    => 'markets',
        'comment' => Comment::factory()->create([
            'commentable_type' => Market::class,
            'commentable_id'   => $market->id,
        ])->id,
    ]));
    $response->assertForbidden();
});

it('can delete market comment.', function () {
    $this->actingAs();

    $market = Market::factory()->create();
    $response = $this->delete(route('comments.destroy', [
        'type'    => 'markets',
        'comment' => Comment::factory()->create([
            'commentable_type' => Market::class,
            'commentable_id'   => $market->id,
            'user_id'          => Auth::id(),
        ])->id,
    ]));
    $response->assertSuccessful();
});

it('cannot update market comment of another user.', function () {
    $this->actingAs();

    $market = Market::factory()->create();
    $response = $this->put(route('comments.update', [
        'type'    => 'markets',
        'comment' => Comment::factory()->create([
            'commentable_type' => Market::class,
            'commentable_id'   => $market->id,
        ])->id,
    ]), [
        'title'          => $t = $this->faker->sentence(),
        'comment'        => $cm = $this->faker->text(),
        'commentable_id' => $market->id,
    ]);
    $response->assertForbidden();
});

it('can update market comment.', function () {
    $this->actingAs();

    $market = Market::factory()->create();
    $response = $this->put(route('comments.update', [
        'type'    => 'markets',
        'comment' => Comment::factory()->create([
            'commentable_type' => Market::class,
            'commentable_id'   => $market->id,
            'user_id'          => Auth::id(),
        ])->id,
    ]), [
        'title'          => $t = $this->faker->sentence(),
        'comment'        => $cm = $this->faker->text(),
        'commentable_id' => $market->id,
    ]);
    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'title'   => $t,
                'comment' => $cm,
            ]
        ]
    ]);
});

it('can get market comments', function () {
    $this->actingAs();

    $response = $this->get(route('comments.index', [
        'type' => 'markets',
    ]));
    $response->assertSuccessful();
});

it('can get a market comments', function () {
    $this->actingAs();

    $response = $this->get(route('comments.show', [
        'type'    => 'markets',
        'comment' => Comment::factory()->create([
            'commentable_type' => Market::class,
            'commentable_id'   => Market::factory()->create()->id,
        ])->id,
    ]));
    $response->assertSuccessful();
});

it('can get an unapproved market comments', function () {
    $this->actingAs();

    $response = $this->get(route('comments.show', [
        'type'    => 'markets',
        'comment' => Comment::factory()->create([
            'commentable_type' => Market::class,
            'commentable_id'   => Market::factory()->create()->id,
            'is_approved'      => false,
        ])->id,
    ]));
    $response->assertForbidden();
});
