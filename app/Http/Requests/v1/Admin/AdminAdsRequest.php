<?php

namespace App\Http\Requests\v1\Admin;

use App\Http\Requests\AppFormRequest;

class AdminAdsRequest extends AppFormRequest
{
    protected ?string $withSlug = "title";

    public function rules(): array
    {
        return [
            "title" => "required|string",
            "duration" => "required|integer",
            "price" => "required|integer",
        ];
    }
}
