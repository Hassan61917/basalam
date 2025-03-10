<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserShopRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "category_id"=>"nullable|exists:categories,id",
            "name"=>"required|string",
            "description"=>"nullable|string",
        ];
    }
}
