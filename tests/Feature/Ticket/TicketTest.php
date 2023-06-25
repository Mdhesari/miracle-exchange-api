<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Helpdesk\Entities\Ticket;

beforeEach(fn() => actingAs());

it('can user with permission get tickets', function () {
    Auth::user()->givePermissionTo('tickets');

    $response = $this->get(route('tickets.index'));

    $response->assertSuccessful();
});

it('can filter status tickets', function () {
    Auth::user()->givePermissionTo('tickets');

    $ticket = Ticket::factory()->for(User::factory())->create([
        'status' => Ticket::STATUS_PENDING_ADMIN,
    ]);

    $response = $this->get(route('tickets.index', [
        'status'  => Ticket::STATUS_PENDING_USER,
        'user_id' => $ticket->user_id,
    ]));

    $data = $response->json('data.items.data');

    $this->assertCount(0, $data);

    $response->assertSuccessful();
});

it('can filter datetime tickets should not get older tickets', function () {
    Auth::user()->givePermissionTo('tickets');

    $count = Ticket::whereBetween('created_at', [now()->subWeek(), now()])->count();

    Ticket::factory()->for(User::factory())->create([
        'created_at' => now()->subMonth(),
    ]);

    $response = $this->get(route('tickets.index', [
        'date_from' => now()->subWeek()->timestamp,
        'date_to'   => now()->timestamp,
    ]));

    $data = $response->json('data.items.data');

    $this->assertCount($count, $data);

    $response->assertSuccessful();
});

it('can filter datetime tickets should get older tickets', function () {
    Auth::user()->givePermissionTo('tickets');

    $count = Ticket::whereBetween('created_at', [now()->subMonth(), now()])->count();

    Ticket::factory()->for(User::factory())->create([
        'created_at' => now()->subMonth(),
    ]);

    $response = $this->get(route('tickets.index', [
        'date_from' => now()->subMonth()->timestamp,
        'date_to'   => now()->timestamp,
    ]));

    $data = $response->json('data.items.data');

    $this->assertCount(++$count, $data);

    $response->assertSuccessful();
});

it('can filter search tickets', function () {
    Auth::user()->givePermissionTo('tickets');

    Ticket::factory()->for(User::factory())->create([
        'subject' => 'Cant login',
    ]);

    $response = $this->get(route('tickets.index', [
        's' => 'Cant login',
    ]));

    $data = $response->json('data.items.data');

    $this->assertCount(1, $data);

    $response->assertSuccessful();
});

it('can filter search tickets should not get un matched', function () {
    Auth::user()->givePermissionTo('tickets');

    Ticket::factory()->for(User::factory())->create([
        'subject' => 'Cant login',
    ]);

    $response = $this->get(route('tickets.index', [
        's' => 'Cant authorize',
    ]));

    $data = $response->json('data.items.data');

    $this->assertCount(0, $data);

    $response->assertSuccessful();
});

it('can filter search tickets by user name', function () {
    Auth::user()->givePermissionTo('tickets');

    $ticket = Ticket::factory()->for(User::factory()->state([
        'last_name' => 'Hesari',
    ]))->create([
        'subject' => 'Cant login',
    ]);

    $response = $this->get(route('tickets.index', [
        //TODO: should also be able to search full text first_name + last_name
        's' => 'Hesari',
    ]));

    $data = $response->json('data.items.data');

    $this->assertCount(1, $data);

    $response->assertSuccessful();
});

it('can create a new tickets', function () {
    $response = $this->post(route('tickets.store'), [
        'subject'    => 'I have a problem...',
        'department' => 'Technical',
        'notes'      => 'Here is my dashboard...',
    ]);

    $response->assertSuccessful()->assertJsonStructure([
        'data' => [
            'item' => [
                'subject', 'department', 'notes',
            ]
        ]
    ])->assertJson([
        'data' => [
            'item' => [
                'status' => Ticket::STATUS_PENDING_ADMIN,
            ]
        ]
    ]);
});
