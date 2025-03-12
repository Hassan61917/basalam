<?php

namespace App\Handlers;

use Illuminate\Database\Eloquent\Model;

abstract class ModelHandler
{
    protected array $rules = [];

    public function handle(Model $model, array $params = []): void
    {
        foreach ($this->rules as $rule) {
            if (method_exists($this, $rule)) {
                $this->$rule($model, $params);
            }
            if (class_exists($rule)) {
                $rule = app()->make($rule);
                $rule->handle($model, $params);
            }
        }
    }
}
