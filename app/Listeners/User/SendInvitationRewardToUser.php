<?php

namespace App\Listeners\User;

use App\Events\User\UserInviterUpdated;
use Illuminate\Support\Facades\Log;
use Modules\Wallet\Actions\CreateDepositTransaction;
use Modules\Wallet\Entities\Transaction;

class SendInvitationRewardToUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserInviterUpdated $event): void
    {
        $user = $event->user;

        $tx = app(CreateDepositTransaction::class)([
            'quantity'    => config('sarrafi.invitation_reward_qua'),
            'user_id'     => $user->inviter_id,
            'status'      => Transaction::STATUS_VERIFIED,
            'verified_at' => now(),
            'reference'   => 'InvitationReward_'.$user->id,
        ]);

        if ($tx?->status != Transaction::STATUS_VERIFIED) {
            Log::error("Could not create reward deposit transaction for inviter.");
        }
    }
}
