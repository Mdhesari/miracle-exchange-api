<?php

namespace Modules\Notification\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification as NotificationManager;
use Modules\Notification\Enums\NotificationStatus;
use Modules\Notification\Events\NotificationCreated;
use Modules\OTPLogin\Notifications\SendChannelNotification;

class SendChannelNotificationListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param NotificationCreated $event
     * @return void
     */
    public function handle(NotificationCreated $event)
    {
        $users = User::query();

        if (! is_null($event->notification->role)) {
            $users->filterRoles([$event->notification->role]);
        }

        NotificationManager::send($users->cursor(), new SendChannelNotification());

        $event->notification->update([
            'status' => NotificationStatus::Success->name,
        ]);
    }
}
