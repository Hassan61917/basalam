<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserOrderRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "product_id" => "required|exists:products,id",
            "quantity" => "nullable|numeric",
        ];
    }
}
