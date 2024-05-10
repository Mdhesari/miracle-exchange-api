<?php

namespace Modules\Notification\Actions;

use Carbon\Carbon;
use Modules\Notification\Entities\Notification;
use Modules\Notification\Enums\NotificationStatus;
use Modules\Notification\Events\NotificationCreated;

class CreateNotification
{
    public function __invoke(array $data)
    {
        if (isset($data['sends_at'])) {
            $data['sends_at'] = Carbon::createFromTimestamp($data['sends_at']);
        }

        //TODO: specific user notification
        $notification = Notification::create([
            ...$data,
            'status'   => isset($data['sends_at']) && $data['sends_at']->greaterThan(now()) ? NotificationStatus::Scheduled->name : NotificationStatus::Pending->name,
            'sends_at' => $data['sends_at'] ?? null,
        ]);

        $event = new NotificationCreated($notification);

        if (isset($data['sends_at']) && $data['sends_at']) {
            dispatch(fn() => event($event))->delay($notification->sends_at);
        } else {
            event($event);
        }

        return $notification;
    }
}
