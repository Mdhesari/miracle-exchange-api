<?php


use Illuminate\Support\Facades\Auth;

beforeEach(fn() => $this->actingAs());

it('can user with permission create user', function () {
    Auth::user()->givePermissionTo('users');

    $response = $this->post(route('users.store'), [
        'mobile' => '9371223040',
    ]);

    $response->assertSuccessful();
});
