<?php

namespace Modules\Wallet\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Modules\Market\Entities\Market;
use Modules\Wallet\Actions\ApplyWalletQueryFilters;
use Modules\Wallet\Actions\CreateDepositTransaction;
use Modules\Wallet\Actions\CreateWithdrawTransaction;
use Modules\Wallet\Entities\Transaction;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Http\Requests\DepositRequest;
use Modules\Wallet\Http\Requests\WithdrawRequest;
use WendellAdriel\ValidatedDTO\Exceptions\CastTargetException;
use WendellAdriel\ValidatedDTO\Exceptions\MissingCastTypeException;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('can:wallets')->except(['withdraw', 'deposit']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ApplyWalletQueryFilters $applyWalletQueryFilters
     * @return JsonResponse
     * @throws ValidationException
     * @throws CastTargetException
     * @throws MissingCastTypeException
     * @LRDparam s string
     * @LRDparam oldest boolean
     * @LRDparam active boolean
     * @LRDparam user_id integer
     * @LRDparam date_from integer
     * @LRDparam date_to integer
     */
    public function index(Request $request, ApplyWalletQueryFilters $applyWalletQueryFilters): JsonResponse
    {
        $query = $applyWalletQueryFilters(Wallet::query(), $request->all());

        return api()->success(null, [
            'items' => $query->paginate(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Wallet $wallet
     * @return JsonResponse
     */
    public function show(Wallet $wallet): JsonResponse
    {
        return api()->success(null, [
            'item' => $wallet,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Wallet $wallet
     * @return JsonResponse
     */
    public function destroy(Wallet $wallet): JsonResponse
    {
        $wallet->delete();

        return api()->success(null);
    }

    /**
     * @param DepositRequest $request
     * @param CreateDepositTransaction $createDepositTransaction
     * @return JsonResponse
     */
    public function deposit(DepositRequest $request, CreateDepositTransaction $createDepositTransaction): JsonResponse
    {
        $hasPermission = $this->hasPermissionToWallets($request->user());

        $market = Market::find($request->market_id);
        $transaction = $createDepositTransaction([
            'transactionable_type' => $market::class,
            'transactionable_id'   => $market->id,
            'currency'             => $market->symbol,
            'user_id'              => $hasPermission ? ($request->user ?: $request->user()->id) : $request->user()->id,
            'quantity'             => $request->quantity,
            'status'               => $hasPermission ? Transaction::STATUS_VERIFIED : Transaction::STATUS_PENDING,
        ]);

        return api()->success(__('wallet::transaction.deposit.success', [
            'user'           => $request->user,
            'quantity'       => $transaction->formatted_qua,
            'total_quantity' => $transaction->wallet->formatted_qua,
        ]), [
            'item' => $transaction->fresh(),
        ]);
    }

    /**
     * @param WithdrawRequest $request
     * @param CreateWithdrawTransaction $createWithdrawTransaction
     * @return JsonResponse
     * @throws ValidationException
     */
    public function withdraw(WithdrawRequest $request, CreateWithdrawTransaction $createWithdrawTransaction): JsonResponse
    {
        $hasPermission = $this->hasPermissionToWallets($request->user());

        $market = Market::find($request->market_id);
        $transaction = $createWithdrawTransaction([
            'transactionable_type' => $market::class,
            'transactionable_id'   => $market->id,
            'crypto_network_id'    => $request->crypto_network_id,
            'crypto_wallet_hash'   => $request->crypto_wallet_hash,
            'currency'             => $market->symbol,
            'user_id'              => $hasPermission ? ($request->user ?: $request->user()->id) : $request->user()->id,
            'quantity'             => $request->quantity,
            'status'               => $hasPermission ? Transaction::STATUS_VERIFIED : Transaction::STATUS_PENDING,
        ]);

        return api()->success(__('wallet::transaction.withdraw.success', [
            'user'           => $request->user,
            'quantity'       => $transaction->formatted_qua,
            'total_quantity' => $transaction->wallet->formatted_qua,
        ]), [
            'item' => $transaction->fresh(),
        ]);
    }

    private function hasPermissionToWallets($user)
    {
        return $user->can('wallets');
    }

}
