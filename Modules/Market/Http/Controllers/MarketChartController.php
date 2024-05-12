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

        $results = DB::table(DB::raw('(SELECT DATE_FORMAT(date, "'.$dateFormat.'") AS datetime, price FROM market_prices WHERE market_id = "'.$market->id.'") AS subquery'))
            ->select([
                DB::raw('datetime'),
                DB::raw('MAX(price) AS high_price'),
                DB::raw('MIN(price) AS low_price'),
                DB::raw('(SELECT price FROM market_prices WHERE market_id = "'.$market->id.'" AND DATE_FORMAT(date, "'.$dateFormat.'") = datetime ORDER BY date ASC LIMIT 1) AS open_price'),
                DB::raw('(SELECT price FROM market_prices WHERE market_id = "'.$market->id.'" AND DATE_FORMAT(date, "'.$dateFormat.'") = datetime ORDER BY date DESC LIMIT 1) AS close_price'),
            ])
            ->groupBy('datetime')
            ->orderBy('datetime')->get();

        return api()->success(null, [
            'items' => $results,
        ]);
    }
}
