<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserBlockRequest extends AppFormRequest
{
    function rules(): array
    {
        return [
            "block_id" => "required|exists:users,id",
            "until" => "nullable",
        ];
    }
}
