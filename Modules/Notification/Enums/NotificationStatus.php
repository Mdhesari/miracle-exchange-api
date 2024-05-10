<?php

namespace Modules\Notification\Enums;

enum NotificationStatus
{
    case Success;
    case Pending;
    case Scheduled;
    case Failed;
}
