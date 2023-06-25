<?php

namespace Modules\RolePermission\Actions;

use Mdhesari\LaravelQueryFilters\Abstract\BaseQueryFilters;

class ApplyRoleQueryFilters extends BaseQueryFilters
{
    public function __invoke($query, array $data)
    {
        parent::__invoke($query, $data);

        $query->with(['permissions', 'users']);

        if ( isset($data['role_id']) ) {
            $query->where('id', $data['role_id']);
        }

        if ( isset($data['permission']) ) {
            $query->whereHas('permissions', fn($query) => $query->whereName($data['permission']));
        }

        return $query;
    }
}
