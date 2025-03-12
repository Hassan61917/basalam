<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserQuestionRequest extends AppFormRequest
{
    protected array $unset = ["shop_id", "product_id"];

    public function rules(): array
    {
        return [
            "shop_id" => "required|exists:shops,id",
            "product_id" => "nullable|exists:products,id",
            "question" => "required|string",
        ];
    }
}

