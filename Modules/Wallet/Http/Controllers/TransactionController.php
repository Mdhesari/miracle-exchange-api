<?php

namespace Modules\Wallet\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
     * @QAparam s string
     * @QAparam type string
     * @QAparam user_id integer
     * @QAparam with_trashed boolean
     * @QAparam quantity integer
     * @QAparam wallet_id integer
     * @QAparam status string [pending, gateway_pending, verified, rejected]
     * @QAparam oldest boolean
     * @QAparam date_from integer
     * @QAparam date_to integer
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
     * @param VerifyTransaction $verifyTransaction
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function verify(VerifyTransactionRequest $request, Transaction $transaction, VerifyTransaction $verifyTransaction): JsonResponse
    {
        $this->authorize('verify', $transaction);

        $verifyTransaction($transaction, $request->validated());

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
