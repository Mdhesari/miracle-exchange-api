<?php

namespace Modules\Wallet\Actions;

use Modules\Wallet\Entities\CryptoNetwork;

class DeleteCryptoNetwork
{
    public function __invoke(CryptoNetwork $cryptoNetwork)
    {
        $cryptoNetwork->delete();
    }
}
