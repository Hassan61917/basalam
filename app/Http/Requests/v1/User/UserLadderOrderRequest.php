<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserLadderOrderRequest extends AppFormRequest
{
    protected array $unset = ["ladder_id"];

    public function rules(): array
    {
        return [
            "ladder_id" => "required|exists:ladders,id",
            "shop_id" => "required|exists:shops,id",
        ];
    }
}
