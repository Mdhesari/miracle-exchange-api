<?php

namespace Modules\Notification\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Notification\Entities\Notification;

class AddNotificationCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Notification $notification
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function handle()
    {
        foreach (User::cursor() as $user) {
            $cacheKey = 'user-notifications.'.$user->id;
            cache()->add($cacheKey, intval(cache()->get($cacheKey) ?: 0) + 1);
        }
    }
}
