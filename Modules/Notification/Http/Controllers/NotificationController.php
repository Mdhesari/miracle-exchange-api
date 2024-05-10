<?php

namespace Modules\Notification\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Notification\Actions\CreateNotification;
use Modules\Notification\Actions\Notification\ApplyNotificationQueryFilters;
use Modules\Notification\Actions\UpdateNotification;
use Modules\Notification\Entities\Notification;
use Modules\Notification\Http\Requests\NotificationRequest;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

        $this->middleware('can:notifications')->only('store', 'getDetails');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ApplyNotificationQueryFilters $applyQueryFilters
     * @return JsonResponse
     */
    public function index(Request $request, ApplyNotificationQueryFilters $applyQueryFilters): JsonResponse
    {
        $query = $applyQueryFilters(Notification::query(), $request->all());

        return api()->success(null, [
            'items' => $query->paginate(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NotificationRequest $request
     * @param CreateNotification $createNotification
     * @return JsonResponse
     */
    public function store(NotificationRequest $request, CreateNotification $createNotification): JsonResponse
    {
        $notification = $createNotification($request->validated());

        return api()->success(null, [
            'item' => Notification::find($notification->id),
        ]);
    }

    public function update(NotificationRequest $request, Notification $notification, UpdateNotification $updateNotification)
    {
        if (! $notification->isScheduled()) {
            return api()->validation(null, [
                'notification' => __('notification.not_scheduled')
            ]);
        }

        $notification = $updateNotification($notification, $request->validated());

        return api()->success(null, [
            'item' => Notification::find($notification->id),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Notification $notification
     * @return JsonResponse
     */
    public function show(Notification $notification): JsonResponse
    {
        return api()->success(null, [
            'item' => Notification::find($notification->id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Notification $notification
     * @return JsonResponse
     */
    public function destroy(Notification $notification): JsonResponse
    {
        $notification->delete();

        return api()->success();
    }

    /**
     * @return JsonResponse
     */
    public function getDetails(): JsonResponse
    {
        return api()->success(null, [
            'item' => [
                'total' => Notification::count(),
            ],
        ]);
    }
}
