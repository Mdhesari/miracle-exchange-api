<?php

namespace Modules\Wallet\Traits;

use Modules\Wallet\Entities\Wallet;

trait HasWallet
{
    public function wallets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function wallet()
    {
        return $this->wallets()->orderBy('quantity', 'desc')->first();
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
