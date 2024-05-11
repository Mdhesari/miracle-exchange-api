<?php

namespace Modules\Notification\Actions\Notification;

use App\Models\User;
use Mdhesari\LaravelQueryFilters\Abstract\BaseQueryFilters;

class ApplyNotificationQueryFilters extends BaseQueryFilters
{
    public function __invoke($query, array $data)
    {
        parent::__invoke($query, $data);

        if (isset($data['status'])) {
            $query->where('status', $data['status']);
        }

        if ($this->user()->cannot('notifications')) {
            $query->where('notifiable_type', User::class)->where('notifiable_id', $this->user()->id);
        }

        return $query;
    }
}
