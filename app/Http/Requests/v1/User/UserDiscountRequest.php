<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\v1\Admin\AdminDiscountRequest;

class UserDiscountRequest extends AdminDiscountRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules["product_id"] = "nullable|exists:products,id";
        return $rules;
    }
}
