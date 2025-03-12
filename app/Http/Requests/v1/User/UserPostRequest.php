<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserPostRequest extends AppFormRequest
{
    protected ?string $withSlug = "title";

    public function rules(): array
    {
        return [
            "title" => "required|string",
            "body" => "required|string",
            "can_comment" => "nullable|boolean",
            "published_at" => "nullable|date",
        ];
    }
}
