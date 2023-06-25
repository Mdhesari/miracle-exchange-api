<?php

use App\Models\User;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\Role;

beforeEach(fn() => actingAs());

it('can user with permission get roles', function () {
    Auth::user()->givePermissionTo('roles');

    $response = $this->get(route('roles.index'));

    $response->assertSuccessful();

    $this->assertNotNull($response->json('data.items.data.0.users'));
    $this->assertNotNull($response->json('data.items.data.0.permissions'));
});

it('cannot user get roles', function () {
    $response = $this->get(route('roles.index'));

    $response->assertForbidden();
});

it('can get roles and user count & permissions count', function () {
    Auth::user()->givePermissionTo('roles');

    $response = $this->get(route('roles.index'));

    $response->assertsuccessful();

    $this->assertNotNull($response->json('data.items.data.0.users_count'));
    $this->assertNotNull($response->json('data.items.data.0.permissions_count'));
});

it('can user with permission create role', function () {
    Auth::user()->givePermissionTo('roles');

    $response = $this->post(route('roles.store'), [
        'name'        => 'test',
        'permissions' => $permissions = Permission::limit(3)->pluck('id')->toArray(),
        'users'       => $users = \App\Models\User::limit(2)->pluck('id')->toArray(),
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'data' => [
                'item' => [
                    'name' => 'test',
                ],
            ],
        ]);

    $role = Role::whereName('test')->first();

    $roleUsers = $role->users()->pluck('id')->toArray();
    $rolePermissions = $role->permissions()->pluck('id')->toArray();

    foreach ($users as $user) {
        $this->assertTrue(in_array($user, $roleUsers));
    }

    foreach ($permissions as $permission) {
        $this->assertTrue(in_array($permission, $rolePermissions));
    }
});

it('can user with permission update role', function () {
    Auth::user()->givePermissionTo('roles');

    $role = Role::create([
        'name'       => 'blue',
        'guard_name' => 'sanctum',
    ]);

    $user = \App\Models\User::factory()->create();

    $user->assignRole($role);

    $this->assertTrue($user->hasRole('blue'));

    $response = $this->put(route('roles.update', $role), [
        'name'        => 'blue',
        'permissions' => [1],
        'users'       => Arr::wrap(Auth::id()),
    ]);

    $response->assertSuccessful();

    $this->assertFalse($user->fresh()->hasRole('blue'));
});

it('can user with permission delete role', function () {
    Auth::user()->givePermissionTo('roles');

    $role = Role::create([
        'name'       => 'blue',
        'guard_name' => 'sanctum',
    ]);

    $response = $this->delete(route('roles.destroy', $role));

    $response->assertSuccessful();

    $this->assertNull($role->fresh());
});

it('cannot user with permission delete a role that is assigned to another user', function () {
    Auth::user()->givePermissionTo('roles');

    $role = Role::whereName('super-admin')->first();

    $response = $this->delete(route('roles.destroy', $role));

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'role' => __('validation.role-conflict', [
                'attribute' => 'role',
            ]),
        ], 'data');
});

it('can revoke all users with a role', function () {
    Auth::user()->givePermissionTo('roles');

    $role = Role::whereName('super-admin')->first();

    $response = $this->put(route('roles.update', $role), [
        'name'        => $role->name,
        'permissions' => $role->permissions()->pluck('id')->toArray(),
        'users'       => [],
    ]);

    $response->assertSuccessful();
});

it('can get all permissions', function () {
    Auth::user()->givePermissionTo('roles');

    $response = $this->get(route('permissions.index'));

    $response->assertSuccessful()
        ->assertJson([
            'data' => [
                'items' => [
                    'total' => Permission::whereGuardName('sanctum')->count(),
                ]
            ]
        ]);
});

it('can user with permission assign users to role', function () {
    Auth::user()->givePermissionTo('roles');

    $users = User::factory()->count(3)->create();

    $response = $this->post(route('roles.assign', $role = Role::whereName('super-admin')->first()), [
        'users' => $users->pluck('id')->toArray(),
    ]);

    $response->assertSuccessful();

    foreach ($users as $user) {
        $this->assertTrue($user->fresh()->hasRole($role));
    }
});

it('can user with permission revoke users from role', function () {
    Auth::user()->givePermissionTo('roles');

    $users = User::role(Role::whereName('super-admin')->first())->get();

    $response = $this->post(route('roles.revoke', $role = Role::whereName('super-admin')->first()), [
        'users' => $users->pluck('id')->toArray(),
    ]);

    foreach ($users as $user) {
        $this->assertFalse($user->fresh()->hasRole($role));
    }

    $response->assertSuccessful();
});
