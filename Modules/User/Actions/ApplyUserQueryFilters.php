<?php

namespace Modules\User\Actions;

use Mdhesari\LaravelQueryFilters\Abstract\BaseQueryFilters;

class ApplyUserQueryFilters extends BaseQueryFilters
{
    public function __invoke($query, array $data)
    {
        parent::__invoke($query, $data);

        if ( isset($data['mobile']) ) {
            $query->where('mobile', 'LIKE', '%'.$data['mobile'].'%');
        }

        if ( isset($data['status']) ) {
            $query->where('status', $data['status']);
        }

        if ( isset($data['type']) ) {
            $query->where('type', $data['type']);
        }

        if ( isset($data['admins']) ) {
            $query->whereHas('roles', function ($query) {
                $query->whereHas('permissions', function ($query) {
                    $query->where('name', 'admin');
                });
            });
        }

        return $query;
    }
}
