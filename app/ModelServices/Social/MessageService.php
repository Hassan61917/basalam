<?php

namespace App\ModelServices\Social;

use App\Events\MessageWasSent;
use App\Exceptions\ModelException;
use App\Handlers\Message\MessageHandler;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MessageService
{
    public function __construct(
        private MessageHandler $messageHandler
    )
    {
    }

    public function inbox(User $user, array $relations = []): HasMany
    {
        return $user->inbox()->withFilters()->with($relations);
    }

    public function outbox(User $user, array $relations = []): HasMany
    {
        return $user->outbox()->withFilters()->with($relations);
    }

    public function getChatsFor(User $user, array $relations = []): Builder
    {
        return Message::chats($user->id)->withFilters()->with($relations);
    }

    public function getChatMessages(User $sender, User $receiver, array $relations = []): Builder
    {
        return Message::chat($sender->id, $receiver->id)->with($relations);
    }

    public function allMessages(array $relations = []): Builder
    {
        return Message::query()->withFilters()->with($relations);
    }

    public function sendMessage(User $sender, array $data): Message
    {
        $message = $sender->outbox()->make($data);
        $message->save();
        return $message;
    }

    public function make(User $user, array $data)
    {
        $message = $user->outbox()->make($data);
        if (!$this->messageHandler->handle($message)) {
            throw new ModelException("message could not be saved");
        }
        $message->save();
        MessageWasSent::dispatch($message);
        return $message;
    }
}
