<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserFollowRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "page_id" => "required|exists:pages,id",
        ];
    }
}
