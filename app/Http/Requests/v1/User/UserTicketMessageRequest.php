<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserTicketMessageRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "body" => "required|string",
            "message_id" => "nullable|exists:ticket_messages,id",
        ];
    }
}
