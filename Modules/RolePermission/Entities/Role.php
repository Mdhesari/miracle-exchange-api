<?php

namespace Modules\RolePermission\Entities;

use Illuminate\Support\Facades\Lang;
use Mdhesari\LaravelQueryFilters\Contracts\Expandable;
use OwenIt\Auditing\Auditable;

class Role extends \Spatie\Permission\Models\Role implements Expandable, \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;

    protected $appends = [
        'name_translation',
        'users_count',
        'permissions_count',
    ];

    public function getNameTranslationAttribute()
    {
        $key = 'rolepermission::roles.'.$this->name;

        return Lang::has($key) ? Lang::get($key) : $this->name;
    }

    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }

    public function getPermissionsCountAttribute()
    {
        return $this->permissions()->count();
    }

    public function getExpandRelations(): array
    {
        return [
            'permissions',
        ];
    }

    public function getSearchParams(): array
    {
        return [
            'name'
        ];
    }
}
