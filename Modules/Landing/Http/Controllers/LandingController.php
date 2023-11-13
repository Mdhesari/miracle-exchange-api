<?php

namespace Modules\Landing\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Landing\Actions\ApplyLandingQueryFilters;
use Modules\Landing\Actions\CreateLanding;
use Modules\Landing\Actions\UpdateLanding;
use Modules\Landing\Entities\Landing;
use Modules\Landing\Http\Requests\LandingRequest;

class LandingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'can:landing'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     * @LRDparam s string
     * @LRDparam date_from string
     * @LRDparam date_to string
     * @LRDparam per_page string
     * @LRDparam per_page string
     * @LRDparam expand string [orders]
     * @LRDparam status Enum [Enabled, Disabled]
     */
    public function index(Request $request, ApplyLandingQueryFilters $applyLandingQueryFilters): JsonResponse
    {
        $query = $applyLandingQueryFilters(Landing::query(), $request->all());

        return api()->success(null, [
            'items' => $query->paginate(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LandingRequest $request, CreateLanding $createLanding): JsonResponse
    {
        $landing = $createLanding($request->validated());

        return api()->success(null, [
            'item' => Landing::find($landing->id),
        ]);
    }

    /**
     * @param Landing $landing
     * @return JsonResponse
     */
    public function show(Landing $landing): JsonResponse
    {
        return api()->success(null, [
            'item' => Landing::find($landing->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LandingRequest $request, Landing $landing, UpdateLanding $updateLanding): JsonResponse
    {
        $landing = $updateLanding($landing, $request->validated());

        return api()->success(null, [
            'item' => Landing::find($landing->id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Landing $landing): JsonResponse
    {
        $landing->delete();

        return api()->success();
    }
}
