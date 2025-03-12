<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserPageRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "username" => "nullable|string|unique:pages,username",
            "is_private" => "nullable|boolean",
            "can_comment" => "nullable|boolean",
        ];
    }
}
