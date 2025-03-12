<?php

namespace App\Handlers\Message;

use App\Models\Message;

interface IMessageHandler
{
    public function canSend(Message $message): bool;
}
