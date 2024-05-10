<?php

namespace Modules\Notification\Actions;

use Modules\Notification\Entities\Notification;

class UpdateNotification
{
    public function __invoke(Notification $notification, array $data): Notification
    {
        $notification->update($data);

        return $notification;
    }
}
