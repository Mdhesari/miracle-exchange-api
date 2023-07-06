<?php

namespace Modules\Order\Actions;

use Illuminate\Support\Facades\Auth;
use Modules\Market\Entities\Market;
use Modules\Order\Entities\Order;

class CreateOrder
{
    public function __construct(
        private PrepareOrderData $prepareOrderData
    )
    {
        //
    }

    public function __invoke(array $data)
    {
        $data = ($this->prepareOrderData)($data);

        if (! isset($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }

        return Order::create($data);

    }
}
