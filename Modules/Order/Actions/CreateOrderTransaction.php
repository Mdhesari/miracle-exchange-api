<?php

namespace Modules\Order\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\Order\Entities\Order;
use Modules\Order\Events\OrderTransactionCreated;
use Modules\Wallet\Entities\Transaction;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Events\WalletWithdraw;

class CreateOrderTransaction
{
    /**
     * @param Order $order
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     * @throws ValidationException
     */
    public function __invoke(Order $order, array $data): \Illuminate\Database\Eloquent\Model
    {
        if (isset($data['use_wallet']) && $data['use_wallet']) {
            /** @var User $user */
            $user = Auth::user();
            /** @var Wallet $wallet */
            $wallet = $user->wallet();
            $usdtQua = $order->toUsdt();
            $wallet->hasBalance($usdtQua) ?: throw ValidationException::withMessages([
                'wallet' => 'wallet::transaction.insufficientBalance',
            ]);

            $wallet->dischargeWallet($usdtQua);

            event(new WalletWithdraw($wallet));

            $data['status'] = Transaction::STATUS_ADMIN_PENDING;
            $data['wallet_id'] = $wallet->id;
            $data['quantity'] = $usdtQua;
        }

        $transaction = $order->transactions()->create([
            'gateway_id' => $data['gateway_id'] ?? null,
            'quantity'   => $data['quantity'] ?? $order->cumulative_quote_quantity,
            'user_id'    => $data['user_id'] ?? Auth::id(),
            'status'     => $data['status'] ?? Transaction::STATUS_GATEWAY_PENDING,
            'type'       => Transaction::TYPE_WITHDRAW,
            'expires_at' => today()->addDay(),
        ]);

        event(new OrderTransactionCreated($transaction));

        return $transaction;
    }
}
