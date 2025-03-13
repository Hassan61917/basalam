<?php

namespace App\Http\Requests\v1\Admin;

use App\Enums\ModelPriority;
use App\Enums\PriorityType;
use App\Http\Requests\AppFormRequest;
use App\Rules\EnumRule;

class AdminReportCategoryRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "priority" => ["required", new EnumRule(PriorityType::class)],
            "name" => "required|string",
            "ban_duration"=>"required|integer|between:1,10",
        ];
    }
}
