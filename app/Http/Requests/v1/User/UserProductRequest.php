<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;
use App\Rules\ProductOptionRule;

class UserProductRequest extends AppFormRequest
{
    protected array $unset = ["category_id"];

    public function rules(): array
    {
        return [
            "category_id" => "required|exists:categories,id",
            'name' => "required|string",
            "description" => "required|string",
            "price" => "required|integer|min:1",
            "available" => "nullable|boolean",
            "hidden" => "nullable|boolean",
            "options" => ["nullable", "array", new ProductOptionRule()],
        ];
    }
}
