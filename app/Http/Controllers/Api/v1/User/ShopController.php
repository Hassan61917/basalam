<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Models\Shop;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;

abstract class ShopController extends AuthUserController
{
    public function before(?Model $model): void
    {
        if (!$this->authUser()->shop) {
            throw new AuthorizationException("you must be a shop owner");
        }
        parent::before($model);
    }
}
