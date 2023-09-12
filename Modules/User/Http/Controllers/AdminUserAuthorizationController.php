<?php

namespace Modules\User\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller;
use Modules\User\Actions\AuthorizeUser;
use Modules\User\Actions\RejectUser;

class AdminUserAuthorizationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'can:users']);
    }

    public function accept(User $user, AuthorizeUser $authorizeUser): \Illuminate\Http\JsonResponse
    {
        $authorizeUser($user);

        return api()->success();
    }

    public function reject(User $user, RejectUser $rejectUser): \Illuminate\Http\JsonResponse
    {
        $rejectUser($user);

        return api()->success();
    }
}
