<?php

namespace Modules\Market\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Market\Entities\Market;

class MarketChartController extends Controller
{
    public function __construct()
    {
        //
    }

    /**
     * @LRDparam timeframe string [yearly, monthly, daily, hourly]
     * @param Request $request
     * @param Market $market
     * @return JsonResponse
     */
    public function __invoke(Request $request, Market $market): JsonResponse
    {
        $dateFormat = match ($request->query('timeframe')) {
            'yearly' => '%Y-00-00 00:00:00',
            'monthly' => '%Y-%m-00 00:00:00',
            'daily' => '%Y-%m-%d 00:00:00',
            'minutely' => '%Y-%m-%d 00:%i:00',
            default => '%Y-%m-%d %H:00:00',
        };

        $marketId = $market->id;

        $results = DB::select("
            SELECT
                datetime,
                high_price,
                low_price,
                (SELECT mp1.price FROM market_prices mp1
                 WHERE mp1.market_id = ?
                 AND DATE_FORMAT(mp1.date, ?) = agg.datetime
                 ORDER BY mp1.date ASC LIMIT 1) AS open_price,
                (SELECT mp2.price FROM market_prices mp2
                 WHERE mp2.market_id = ?
                 AND DATE_FORMAT(mp2.date, ?) = agg.datetime
                 ORDER BY mp2.date DESC LIMIT 1) AS close_price
            FROM (
                SELECT
                    DATE_FORMAT(date, ?) AS datetime,
                    MAX(price) AS high_price,
                    MIN(price) AS low_price
                FROM market_prices
                WHERE market_id = ?
                GROUP BY DATE_FORMAT(date, ?)
            ) AS agg
            ORDER BY datetime ASC
        ", [$marketId, $dateFormat, $marketId, $dateFormat, $dateFormat, $marketId, $dateFormat]);

        return api()->success(null, [
            'items' => $results,
        ]);
    }
}
