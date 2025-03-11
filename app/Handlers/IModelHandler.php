<?php

namespace App\Handlers;

use Illuminate\Database\Eloquent\Model;

interface IModelHandler
{
    public function handle(Model $model, array $params = []): void;
}
