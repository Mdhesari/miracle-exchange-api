<?php

namespace Modules\Comment\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Comment\Entities\Comment;

class CommentCreated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Comment $comment
    )
    {
        //
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
