<?php

namespace Modules\Order\Actions;

use Illuminate\Support\Facades\Auth;
use Modules\Market\Entities\Market;
use Modules\Order\Entities\Order;

class CreateOrder
{
    public function __invoke(array $data)
    {
        $market = Market::find($data['market_id']);

        $data['original_market_price'] = $market->price;
        $data['executed_price'] = $market->price;
        $data['executed_quantity'] = floatval(number_format(round($data['cumulative_quote_quantity'] / $market->price, 2), 2, null, null));
        $data['cumulative_quote_quantity'] = $data['executed_quantity'] * floatval($market->price);

        if ( ! isset($data['user_id']) ) {
            $data['user_id'] = Auth::id();
        }

        return Order::create($data);

    }
}
