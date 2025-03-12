<?php

namespace App\Handlers\Message;

use App\Models\Message;

class MessageHandler
{
    protected array $rules = [
        CheckReceiver::class
    ];

    public function handle(Message $message): bool
    {
        foreach ($this->rules as $rule) {
            if (!$rule->canSend($message)) {
                return false;
            }
        }
        return true;
    }
}
