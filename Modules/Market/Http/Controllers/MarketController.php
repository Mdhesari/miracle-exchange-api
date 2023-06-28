<?php

namespace Modules\Market\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Market\Entities\Market;

class MarketController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'can:markets'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Market $market)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Market $market)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Market $market)
    {
        //
    }
}
