<?php

namespace Modules\Order\Actions;

use Modules\Market\Entities\Market;

class PrepareOrderData
{
    public function __invoke($data)
    {
        $market = Market::find($data['market_id']);

        $data['original_market_price'] = $market->price;
        $data['executed_price'] = $market->total_price;
        $data['original_cumulative_quote_quantity'] = $data['cumulative_quote_quantity'];
        $data['executed_quantity'] = round($data['cumulative_quote_quantity'] / $market->total_price, 2);
        $data['cumulative_quote_quantity'] = $data['executed_quantity'] * floatval($market->total_price);

        return $data;
    }
}
