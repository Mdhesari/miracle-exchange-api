<?php

use App\Events\User\UserInviterUpdated;
use App\Listeners\User\SendInvitationRewardToUser;
use App\Models\User;

it('inviter user gets correct amount of reward after invitation', function () {
    $user = User::factory()->create([
        'inviter_id' => ($inviter = User::factory()->create())->id,
    ]);

    $inviter_old_balance = $inviter->wallet_quantity;

    $event = new UserInviterUpdated($user);
    $listener = new SendInvitationRewardToUser;
    $listener->handle($event);

    // make sure that a new reward transaction is created
    $this->assertEquals($inviter_old_balance + config('sarrafi.invitation_reward_qua'), $inviter->fresh()->wallet_quantity);
});
