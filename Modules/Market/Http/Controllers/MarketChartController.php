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
        $dateFormat = "%Y-%m-%d %H:00:00";
        $results = DB::table(DB::raw('(SELECT DATE_FORMAT(date, '.$dateFormat.') AS datetime, price FROM market_prices WHERE market_id = "'.$market->id.'") AS subquery'))
            ->select([
                DB::raw('datetime'),
                DB::raw('MAX(price) AS high_price'),
                DB::raw('MIN(price) AS low_price'),
                DB::raw('(SELECT price FROM market_prices WHERE market_id = "'.$market->id.'" AND DATE_FORMAT(date, '.$dateFormat.') = datetime ORDER BY date ASC LIMIT 1) AS open_price'),
                DB::raw('(SELECT price FROM market_prices WHERE market_id = "'.$market->id.'" AND DATE_FORMAT(date, '.$dateFormat.') = datetime ORDER BY date DESC LIMIT 1) AS close_price'),
            ])
            ->groupBy('datetime')
            ->orderBy('datetime')->get();

        return api()->success(null, [
            'items' => $results,
        ]);
    }
}
