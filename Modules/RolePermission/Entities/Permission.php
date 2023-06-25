<?php

namespace Modules\RolePermission\Entities;

use Illuminate\Support\Facades\Lang;
use OwenIt\Auditing\Auditable;

class Permission extends \Spatie\Permission\Models\Permission implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;

    protected $appends = [
        'name_translation',
    ];

    public function getNameTranslationAttribute()
    {
        $key = 'rolepermission::permissions.'.$this->name;

        return Lang::has($key) ? Lang::get($key) : $this->name;
    }
}
