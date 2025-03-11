<?php

namespace App\Http\Requests\v1\Admin;

use App\Http\Requests\AppFormRequest;

class AdminDiscountRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "title" => "required|string",
            "description" => "required|string",
            "code" => "required|string",
            "category_id" => "nullable|exists:categories,id",
            "client_id" => "nullable|exists:users,id",
            "limit" => "nullable|integer",
            "amount" => "nullable|numeric",
            "percent" => "nullable|numeric",
            "total_balance" => "nullable|numeric",
            "max_amount" => "nullable|numeric",
            "expired_at" => "nullable",
        ];
    }
}
