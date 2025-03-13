<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserReportRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "model" => ["required", "string"],
            "model_id" => "required|numeric",
            "category_id" => "required|numeric|exists:report_categories,id",
            "body" => "required|string",
        ];
    }
}
