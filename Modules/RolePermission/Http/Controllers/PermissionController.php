<?php

namespace Modules\RolePermission\Http\Controllers;

use Illuminate\Http\Request;
use Mdhesari\LaravelQueryFilters\Actions\ApplyQueryFilters;
use Modules\RolePermission\Entities\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'auth:sanctum', 'can:roles',
        ]);
    }

    public function index(Request $request, ApplyQueryFilters $applyQueryFilters)
    {
        $permissions = $applyQueryFilters(Permission::query(), $request->all());

        return api()->success(null, [
            'items' => $permissions->paginate(),
        ]);
    }
}
