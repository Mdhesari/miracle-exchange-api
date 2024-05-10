<?php

namespace Modules\Wallet\Actions;

use Modules\Wallet\Entities\CryptoNetwork;
use Modules\Wallet\Http\Requests\CryptoNetworkRequest;

class UpdateCryptoNetwork
{
    public function __invoke(CryptoNetwork $cryptoNetwork, CryptoNetworkRequest $req)
    {
        $cryptoNetwork->update([
            'name'      => $req->name,
            'is_active' => $req->isActive,
        ]);
    }
}
