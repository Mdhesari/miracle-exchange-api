<?php

namespace Modules\User\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller;
use Modules\User\Actions\AuthorizeUser;

class AdminUserAuthorizationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'can:users']);
    }

    public function __invoke(User $user, AuthorizeUser $authorizeUser): \Illuminate\Http\JsonResponse
    {
        $authorizeUser($user);

        return api()->success();
    }
}
