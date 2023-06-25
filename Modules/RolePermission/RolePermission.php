<?php

namespace Modules\RolePermission;

class RolePermission
{
    public static function getUserModel()
    {
        return resolve(config('rolepermission.user_model'));
    }
}
