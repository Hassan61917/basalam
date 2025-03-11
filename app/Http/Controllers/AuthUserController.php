<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\CheckOwner;
use Illuminate\Database\Eloquent\Model;

class AuthUserController extends Controller
{
    use CheckOwner;

    public function before(?Model $model): void
    {
        $this->isOwner($model);
    }
}
