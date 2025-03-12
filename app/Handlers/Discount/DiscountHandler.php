<?php

namespace App\Handlers\Discount;

use App\Handlers\Discount\Rules\CheckClient;
use App\Handlers\Discount\Rules\CheckExpired;
use App\Handlers\Discount\Rules\CheckLimit;
use App\Handlers\Discount\Rules\CheckTotalBalance;
use App\Handlers\ModelHandler;

class DiscountHandler extends ModelHandler
{
    protected array $rules = [
        CheckExpired::class,
        CheckClient::class,
        CheckLimit::class,
        CheckTotalBalance::class,
    ];
}
