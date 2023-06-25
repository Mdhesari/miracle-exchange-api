<?php

namespace Modules\RolePermission\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;
}
