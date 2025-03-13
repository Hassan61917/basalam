<?php

namespace App\Http\Requests\v1\Admin;

use App\Http\Requests\AppFormRequest;

class AdminLadderRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "category" => "required|exists:categories,id",
            "duration" => "required|numeric",
            "price" => "required|numeric",
        ];
    }
}
