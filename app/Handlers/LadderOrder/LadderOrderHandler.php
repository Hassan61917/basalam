<?php

namespace App\Handlers\LadderOrder;

use App\Exceptions\ModelException;
use App\Handlers\ModelHandler;
use App\Models\LadderOrder;

class LadderOrderHandler extends ModelHandler
{
    protected array $rules = [
        "shop", "user", "category"
    ];

    protected function shop(LadderOrder $order): void
    {
        if (!$order->shop->isAvailable()) {
            throw new ModelException("only available shops can be laddered");
        }
    }

    protected function user(LadderOrder $order): void
    {
        if (!$order->user->is($order->shop->user)) {
            throw new ModelException("you can ladder your own ads");
        }
    }

    protected function category(LadderOrder $order): void
    {
        if (!$order->ads->category->is($order->shop->category)) {
            throw new ModelException("category must be same");
        }
    }
}
