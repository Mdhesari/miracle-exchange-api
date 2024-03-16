<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\CommentCreatedNotification;
use Illuminate\Support\Facades\Notification;
use Modules\Comment\Events\CommentCreated;

class SendCommentNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentCreated $event): void
    {
        Notification::send(User::permission('telegram')->whereNotNull('telegram_user_id')->get(), new CommentCreatedNotification($event->comment));
    }
}
