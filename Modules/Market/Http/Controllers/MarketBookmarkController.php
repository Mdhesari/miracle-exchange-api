<?php

namespace Modules\Market\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Market\Entities\Market;

class MarketBookmarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function __invoke(Request $request, Market $market): \Illuminate\Http\JsonResponse
    {
        $market->bookmarks()->toggle($request->user()->id);

        return api()->success();
    }
}
