<?php

namespace Modules\Notification\Actions\Notification;

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
            $query->where('role', null)->orWhereIn('role', $this->user()->roles()->pluck('name'));
        }

        return $query;
    }
}
