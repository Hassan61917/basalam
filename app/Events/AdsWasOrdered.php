<?php

namespace App\Events;

use App\Models\AdvertiseOrder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdsWasOrdered
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public AdvertiseOrder $order
    )
    {
        //
    }
}
