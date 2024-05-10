<?php

namespace Modules\Notification\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\HasDatabaseNotifications;
use Illuminate\Notifications\RoutesNotifications;
use Modules\Notification\Entities\Notification;

trait Notifiable
{
    use HasDatabaseNotifications, RoutesNotifications;

    abstract public function morphMany($related, $name, $type = null, $id = null, $localKey = null);

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}
