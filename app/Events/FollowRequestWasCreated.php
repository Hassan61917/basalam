<?php

namespace App\Events;

use App\Models\Follow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FollowRequestWasCreated
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Follow $request
    )
    {
        //
    }
}
