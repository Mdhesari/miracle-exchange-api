<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Modules\User\Actions\UploadUserDocuments;
use Modules\User\Http\Requests\UserAuthorizationRequest;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class UserAuthorizationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param UserAuthorizationRequest $request
     * @param User $user
     * @param UploadUserDocuments $uploadUserDocuments
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function __invoke(UserAuthorizationRequest $request, User $user, UploadUserDocuments $uploadUserDocuments): JsonResponse
    {
        $this->authorize('update', $user);

        $uploadUserDocuments($user, $request->validated());

        return api()->success();
    }
}
