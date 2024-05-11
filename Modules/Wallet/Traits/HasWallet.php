<?php

namespace Modules\Wallet\Traits;

use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Actions\SetupWallet;
use Modules\Wallet\Entities\Wallet;

trait HasWallet
{
    public function wallets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function wallet()
    {
        $wallet = $this->wallets()->orderBy('quantity', 'desc')->first();
        if (! $wallet) {
            $wallet = app(SetupWallet::class)([
                'user_id' => Auth::id(),
            ]);
        }

        return $wallet;
    }

    public function getWalletQuantityAttribute()
    {
        return $this->wallet()?->quantity;
    }

    public function getWalletFormattedQuaAttribute()
    {
        return \Modules\Wallet\Wallet::formatMoney($this->wallet()?->quantity);
    }

    /**
     * User general name like email
     *
     * @return mixed
     */
    abstract public function getSubject(): mixed;
}
