<?php

use App\Models\User;
use App\Notifications\WelcomeNotification;

it('can send welcome notification', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $user->notify(new WelcomeNotification);

    $this->assertCount(1, $user->notifications);
});
