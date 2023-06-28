<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

/**
 * User Login/Register tests are in the OTP modules, in /modules directory of the project root
 */

beforeEach(fn() => actingAs());

it('cannot create the same user with mobile', function () {
    Auth::user()->givePermissionTo('users');

    $user = User::first();

    $response = $this->post(route('users.store'), [
        'mobile' => '0'.$user->mobile,
    ]);

    $response->assertStatus(422);
});

it('can restore user', function () {
    Auth::user()->givePermissionTo('users');

    ($user = User::whereMobile('9370038157')->first())->delete();

    $response = $this->put(route('users.restore', $user));

    $response->assertSuccessful();
});

it('can recreate deleted users', function () {
    Auth::user()->givePermissionTo('users');

    User::whereMobile('9370038157')->first()->delete();

    $response = $this->post(route('users.store'), [
        'mobile' => '9370038157',
    ]);

    $response->assertSuccessful();
});

it('can user with permission get admins', function () {
    Auth::user()->givePermissionTo('users');

    $user = User::factory()->create();

    $response = $this->get(route('users.index', [
        'admins'   => true,
        'per_page' => User::count(),
    ]));

    $response->assertSuccessful();

    $users = collect($response->json('data.items.data'))->pluck('id');

    $this->assertFalse(in_array($user->id, $users->toArray()));
});

it('can get profile', function () {
    $response = $this->get(route('profile.index'));

    $response->assertSuccessful();
});

it('can user with permission create user with avatar', function () {
    Auth::user()->givePermissionTo('users');

    $response = $this->post(route('users.store', [
        'expand' => 'media',
    ]), [
        'mobile' => '9371223040',
        'avatar' => UploadedFile::fake()->image('avatar.jpg'),
    ]);

    $response->assertSuccessful();

    $this->assertNotEmpty($response->json('data.item.media.0.original_url'));
});

it('can user with permission create user', function () {
    Auth::user()->givePermissionTo('users');

    $response = $this->post(route('users.store'), [
        'mobile' => '9371223040',
    ]);

    $response->assertSuccessful();
});

it('can user with permission create user with roles', function () {
    Auth::user()->givePermissionTo('users');

    $response = $this->post(route('users.store', [
        'expand' => 'roles',
    ]), [
        'mobile' => '9371223040',
        'roles'  => Role::where('name', 'super-admin')->pluck('id')->toArray(),
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'data' => [
                'item' => [
                    'roles' => [
                        [
                            'name' => 'super-admin',
                        ],
                    ]
                ]
            ]
        ]);
});

it('can update user', function () {
    Auth::user()->givePermissionTo('users');

    $user = \Illuminate\Support\Facades\Auth::user();

    $this->assertNotNull($user->mobile_verified_at);

    $response = $this->put(route('users.update', $user), [
        'mobile' => '9105667705',
    ]);

    $response->assertSuccessful();

    $this->assertNull($user->fresh()->mobile_verified_at);
});

it('can update user profile', function () {
    $user = Auth::user();

    $response = $this->put(route('users.update', $user), [
        'email'  => 'mdhesari99@gmail.com',
        'mobile' => '9123014050',
    ]);

    $response->assertSuccessful()->assertJson([
        'data' => [
            'item' => [
                'email' => 'mdhesari99@gmail.com',
            ]
        ]
    ]);
});
