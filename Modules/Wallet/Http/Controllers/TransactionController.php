<?php

namespace Modules\Wallet\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Actions\CreateAdminOrderTransaction;
use Modules\Wallet\Actions\Transaction\ApplyTransactionQueryFilters;
use Modules\Wallet\Actions\UpdateReference;
use Modules\Wallet\Actions\VerifyTransaction;
use Modules\Wallet\Entities\Transaction;
use Modules\Wallet\Http\Requests\ReferenceTransactionRequest;
use Modules\Wallet\Http\Requests\RejectTransactionRequest;
use Modules\Wallet\Http\Requests\VerifyTransactionRequest;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware([
            'auth:api',
        ])->except('show');
    }

    /**
     * @param Request $request
     * @param ApplyTransactionQueryFilters $applyTransactionQueryFilters
     * @LRDparam s string
     * @LRDparam type string
     * @LRDparam user_id integer
     * @LRDparam with_trashed boolean
     * @LRDparam quantity integer
     * @LRDparam wallet_id integer
     * @LRDparam status string [pending, gateway_pending, verified, rejected]
     * @LRDparam oldest boolean
     * @LRDparam date_from integer
     * @LRDparam date_to integer
     * @return JsonResponse
     */
    public function index(Request $request, ApplyTransactionQueryFilters $applyTransactionQueryFilters): JsonResponse
    {
        $query = $applyTransactionQueryFilters(Transaction::query(), $request->all());

        return api()->success(null, [
            'items' => $query->paginate(),
        ]);
    }

    /**
     * @param Request $request
     * @param Transaction $transaction
     * @return JsonResponse
     */
    public function show(Request $request, Transaction $transaction): JsonResponse
    {
        return api()->success(null, [
            'item' => Transaction::find($transaction->id),
        ]);
    }

    /**
     * @param VerifyTransactionRequest $request
     * @param Transaction $transaction
     * @param CreateAdminOrderTransaction $createAdminOrderTransaction
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function verify(VerifyTransactionRequest $request, Transaction $transaction, CreateAdminOrderTransaction $createAdminOrderTransaction): JsonResponse
    {
        $this->authorize('verify', $transaction);

        $transaction->verify();

        $transaction = $createAdminOrderTransaction($transaction->transactionable, $request->validated());

        return api()->success(null, [
            'item' => Transaction::find($transaction->id),
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function reject(RejectTransactionRequest $request, Transaction $transaction): JsonResponse
    {
        $this->authorize('reject', $transaction);

        $transaction->reject($request->input('reject_reason'));

        return api()->success(null, [
            'item' => Transaction::find($transaction->id),
        ]);
    }

    /**
     * @param ReferenceTransactionRequest $request
     * @param Transaction $transaction
     * @param UpdateReference $updateReference
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function updateReference(ReferenceTransactionRequest $request, Transaction $transaction, UpdateReference $updateReference): JsonResponse
    {
        $this->authorize('reference', $transaction);

        $transaction = $updateReference($transaction, $request->validated());

        return api()->success(null, [
            'item' => Transaction::find($transaction->id)
        ]);
    }
}
