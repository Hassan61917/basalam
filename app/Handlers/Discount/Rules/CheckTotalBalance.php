<?php

namespace App\Handlers\Discount\Rules;

use App\Exceptions\ModelException;
use App\Handlers\IModelHandler;
use App\Models\Discount;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class CheckTotalBalance implements IModelHandler
{
    public function handle(Model|Discount $model, array $params = []): void
    {
        $order = $params[0];
        if (!$this->canApply($order, $model)) {
            throw new ModelException("discount can not be applied");
        }
    }
    public function canApply(Order $order, Discount $discount): bool
    {
        return !$discount->total_balance || $order->total_price > $discount->total_balance;
    }


}
