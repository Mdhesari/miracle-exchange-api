<?php

namespace Modules\Wallet\Actions;


use Modules\Wallet\Entities\CryptoNetwork;
use Modules\Wallet\Http\Requests\CryptoNetworkRequest;

class CreateCryptoNetwork
{
    public function __invoke(CryptoNetworkRequest $req)
    {
        return CryptoNetwork::create([
            'name'      => $req->name,
            'is_active' => $req->isActive,
            'fee'       => $req->fee,
        ]);
    }
}
