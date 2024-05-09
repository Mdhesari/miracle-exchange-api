<?php

namespace Modules\Market\Http\Controllers;

use App\Http\Controllers\Controller;
use Flowframe\Trend\Trend;
use Modules\Market\Entities\Market;

class MarketChartController extends Controller
{
    public function __construct()
    {
        //
    }

    public function __invoke(Market $market)
    {

    }
}
