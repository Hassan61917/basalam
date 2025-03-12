<?php

namespace App\Http\Requests\v1\Admin;

use App\Http\Requests\AppFormRequest;

class AdminCommissionRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "category_id" => "required|exists:categories,id",
            "percent" => "required|numeric|between:1,100",
            "max_amount" => "required|numeric",
            "applied_at" => "nullable|date",
            "expired_at" => "nullable|date",
        ];
    }
}
