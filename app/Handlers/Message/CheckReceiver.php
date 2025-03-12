<?php

namespace App\Handlers\Message;

use App\Models\Message;
use App\ModelServices\Social\BlockService;

class CheckReceiver implements IMessageHandler
{
    public function __construct(
        protected BlockService $blockService
    )
    {
    }

    public function canSend(Message $message): bool
    {
        return !$this->blockService->isBlocked(
            $message->receiver,
            $message->sender
        );
    }
}
