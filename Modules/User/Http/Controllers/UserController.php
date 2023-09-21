<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\User\Actions\ApplyUserQueryFilters;
use Modules\User\Actions\CreateUser;
use Modules\User\Actions\OnboardUser;
use Modules\User\Events\UserRestored;
use Modules\User\Http\Requests\UserRequest;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Throwable;
use WendellAdriel\ValidatedDTO\Exceptions\CastTargetException;
use WendellAdriel\ValidatedDTO\Exceptions\MissingCastTypeException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

        $this->middleware('can:users')->except([
            'show', 'update', 'destroy',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ApplyUserQueryFilters $applyUserQueryFilters
     * @return JsonResponse
     * @throws ValidationException
     * @throws CastTargetException
     * @throws MissingCastTypeException
     * @LRDparam s string
     * @LRDparam mobile string
     * @LRDparam type string ['Legal', 'Real']
     * @LRDparam status string ['Enabled', 'Disabled', 'Pending', 'AdminPending']
     * @LRDparam expand string ['media']
     * @LRDparam national_code string
     * @LRDparam user_id integer
     * @LRDparam investors boolean
     * @LRDparam funders boolean
     * @LRDparam admins boolean
     * @LRDparam oldest boolean
     * @LRDparam date_from integer
     * @LRDparam date_to integer
     */
    public function index(Request $request, ApplyUserQueryFilters $applyUserQueryFilters): JsonResponse
    {
        $query = $applyUserQueryFilters(User::query(), $request->all());

        return api()->success(null, [
            'items' => $query->paginate(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @param CreateUser $createUser
     * @return JsonResponse
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function store(UserRequest $request, CreateUser $createUser): JsonResponse
    {
        $user = $createUser($request->validated());

        return api()->success(null, [
            'item' => User::find($user->id),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(User $user): JsonResponse
    {
        $this->authorize('show', $user);

        return api()->success(null, [
            'item' => User::find($user->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param User $user
     * @param OnboardUser $onboardUser
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws Throwable
     */
    public function update(UserRequest $request, User $user, OnboardUser $onboardUser): JsonResponse
    {
        $this->authorize('update', $user);

        $user = $onboardUser($user, $request->validated());

        return api()->success(null, [
            'item' => User::find($user->id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('destroy', $user);

        $user->delete();

        return api()->success();
    }

    /**
     * @param $user_id
     * @return JsonResponse
     */
    public function restore($user_id): JsonResponse
    {
        ($user = User::onlyTrashed()->findOrFail($user_id))->restore();

        event(new UserRestored($user));

        return api()->success(null, [
            'item' => User::find($user->id),
        ]);
    }
}
