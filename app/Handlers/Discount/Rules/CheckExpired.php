<?php

namespace App\Handlers\Discount\Rules;

use App\Exceptions\ModelException;
use App\Handlers\IModelHandler;
use App\Models\Discount;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CheckExpired implements IModelHandler
{
    public function handle(Model|Discount $model, array $params = []): void
    {
        $order = $params[0];
        if (!$this->canApply($order, $model)) {
            throw new ModelException("discount can not be applied");
        }
    }

    private function canApply(Order $order, Discount $discount): bool
    {
        return !$discount->expired_at || Carbon::make($discount->expired_at)->gt(now());
    }
}
