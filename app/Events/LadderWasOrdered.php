<?php

namespace App\Events;

use App\Models\LadderOrder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LadderWasOrdered
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public LadderOrder $order
    )
    {
        //
    }
}
