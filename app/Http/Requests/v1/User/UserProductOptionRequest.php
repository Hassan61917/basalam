<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserProductOptionRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "key" => "required|string",
            "value" => "required|string",
        ];
    }
}
