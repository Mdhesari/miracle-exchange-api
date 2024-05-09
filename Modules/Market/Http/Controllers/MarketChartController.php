<?php

namespace Modules\Market\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Market\Entities\Market;

class MarketChartController extends Controller
{
    public function __construct()
    {
        //
    }

    public function __invoke(Market $market)
    {
        $results = DB::table(DB::raw('(SELECT DATE_FORMAT(date, "%Y-%m-%d 00:00:00") AS session_hour, price FROM market_prices WHERE market_id = "9bfdca97-b26a-4811-8cd9-96edab4fc7bf") AS subquery'))
            ->select([
                DB::raw('session_hour'),
                DB::raw('MAX(price) AS high_price'),
                DB::raw('MIN(price) AS low_price'),
                DB::raw('(SELECT price FROM market_prices WHERE market_id = "9bfdca97-b26a-4811-8cd9-96edab4fc7bf" AND DATE_FORMAT(date, "%Y-%m-%d 00:00:00") = session_hour ORDER BY date ASC LIMIT 1) AS open_price'),
                DB::raw('(SELECT price FROM market_prices WHERE market_id = "9bfdca97-b26a-4811-8cd9-96edab4fc7bf" AND DATE_FORMAT(date, "%Y-%m-%d 00:00:00") = session_hour ORDER BY date DESC LIMIT 1) AS close_price'),
            ])
            ->groupBy('session_hour')
            ->orderBy('session_hour')->get();

        return api()->success(null, [
            'items' => $results,
        ]);
    }
}
