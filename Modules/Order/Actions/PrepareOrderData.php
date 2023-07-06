<?php

namespace Modules\Order\Actions;

use Modules\Market\Entities\Market;

class PrepareOrderData
{
    public function __invoke($data)
    {
        $market = Market::find($data['market_id']);

        $data['original_market_price'] = $market->price;
        $data['executed_price'] = $market->price;
        $data['original_cumulative_quote_quantity'] = $data['cumulative_quote_quantity'];
        $data['executed_quantity'] = round($data['cumulative_quote_quantity'] / $market->price, 2);
        $data['cumulative_quote_quantity'] = $data['executed_quantity'] * floatval($market->price);

        return $data;
    }
}
