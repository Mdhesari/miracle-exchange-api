<?php

namespace Modules\Helpdesk\Actions;

use Mdhesari\LaravelQueryFilters\Abstract\BaseQueryFilters;

class ApplyTicketQueryFilters extends BaseQueryFilters
{
    public function __invoke($query, array $data)
    {
        parent::__invoke($query, $data);

        if ( $this->user()->cannot('tickets') ) {
            $query->where('user_id', $this->user()->id);
        }

        if ( isset($data['status']) ) {
            $query->where('status', $data['status']);
        }

        if ( isset($data['department']) ) {
            $query->where('department', $data['department']);
        }

        return $query;
    }
}
