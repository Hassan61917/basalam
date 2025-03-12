<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserReviewRequest extends AppFormRequest
{
    protected array $unset = ["order_id"];

    public function rules(): array
    {
        return [
            "order_id" => "required|exists:order_items,id",
            "rate" => "required|integer|between:1,5",
            "body" => "nullable|string",
        ];
    }
}
