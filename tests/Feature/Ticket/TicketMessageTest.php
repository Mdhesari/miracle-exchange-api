<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Modules\Helpdesk\Entities\Ticket;

beforeEach(fn() => actingAs());

it('can send ticket message with attachments', function () {
    $ticket = Ticket::factory()->create([
        'status'  => Ticket::STATUS_PENDING_ADMIN,
        'user_id' => Auth::id(),
    ]);

    $response = $this->post(route('tickets.messages.store', [
        'ticket' => $ticket,
        'expand' => 'media'
    ]), [
        'message'     => 'hi there',
        'attachments' => [
            UploadedFile::fake()->image('test.png')
        ]
    ]);

    $response->assertSuccessful();

    $this->assertNotNull($response->json('data.item.media.0'));
});

it('is ticket status changed after new ticket message', function () {
    $ticket = Ticket::factory()->create([
        'status'  => Ticket::STATUS_PENDING_USER,
        'user_id' => Auth::id(),
    ]);

    $response = $this->post(route('tickets.messages.store', $ticket), [
        'message' => 'hi there',
    ]);

    $response->assertSuccessful();

    $this->assertEquals(Ticket::STATUS_PENDING_ADMIN, $ticket->fresh()->status);
});
