<?php

namespace Modules\Notification\Observers;

use Modules\Notification\Entities\Notification;
use Modules\Notification\Enums\NotificationStatus;
use Modules\Notification\Jobs\AddNotificationCache;

class NotificationObserver
{
    public function creating(Notification $notification)
    {
        if (! $notification->status) {
            $notification->status = NotificationStatus::Pending->name;
        }
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function created(Notification $notification)
    {
        $this->storeNotificationCacheForUsers($notification);
    }

    public function updated(Notification $notification)
    {
        $this->storeNotificationCacheForUsers($notification);
    }

    private function storeNotificationCacheForUsers(Notification $notification)
    {
        if ($notification->status === NotificationStatus::Success) {
            dispatch(new AddNotificationCache($notification));
        }
    }
}
