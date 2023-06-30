<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mdhesari\LaravelQueryFilters\Actions\ApplyQueryFilters;
use Modules\Account\Actions\ApplyAccountQueryFilters;
use Modules\Account\Actions\CreateAccount;
use Modules\Account\Actions\UpdateAccount;
use Modules\Account\Entities\Account;
use Modules\Account\Http\Requests\AccountRequest;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     * @LRDparam s string
     * @LRDparam date_from string
     * @LRDparam date_to string
     * @LRDparam per_page string
     * @LRDparam per_page string
     * @LRDparam user_id integer [only for admins]
     * @LRDparam expand string [user]
     */
    public function index(Request $request, ApplyAccountQueryFilters $applyQueryFilters): JsonResponse
    {
        $query = $applyQueryFilters(Account::query(), $request->all());

        return api()->success(null, [
            'items' => $query->paginate(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccountRequest $request, CreateAccount $createAccount): JsonResponse
    {
        $account = $createAccount($request->validated());

        return api()->success(null, [
            'item' => Account::find($account->id),
        ]);
    }

    /**
     * Display the specified resource.
     * @throws AuthorizationException
     */
    public function show(Account $account): JsonResponse
    {
        $this->authorize('show', $account);

        return api()->success(null, [
            'item' => Account::find($account->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @throws AuthorizationException
     */
    public function update(AccountRequest $request, Account $account, UpdateAccount $updateAccount): JsonResponse
    {
        $this->authorize('update', $account);

        $account = $updateAccount($account, $request->validated());

        return api()->success(null, [
            'item' => Account::find($account->id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @throws AuthorizationException
     */
    public function destroy(Account $account): JsonResponse
    {
        $this->authorize('destroy', $account);

        $account->delete();

        return api()->success();
    }
}
