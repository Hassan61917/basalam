<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\Common\UserMessageRequest;
use App\Http\Resources\v1\MessageResource;
use App\Models\Message;
use App\Models\User;
use App\ModelServices\Social\MessageService;
use Illuminate\Http\JsonResponse;

class UserMessageController extends AuthUserController
{
    protected string $resource = MessageResource::class;
    protected ?string $ownerName = "sender";
    public function __construct(
        protected MessageService $messageService,
    )
    {
    }

    public function inbox(): JsonResponse
    {
        $messages = $this->messageService->inbox($this->authUser(), ["sender"]);
        return $this->ok($this->paginate($messages));
    }

    public function outbox(): JsonResponse
    {
        $messages = $this->messageService->outbox($this->authUser(), ["receiver"]);
        return $this->ok($this->paginate($messages));
    }

    public function chats(): JsonResponse
    {
        $message = $this->messageService->getChatsFor($this->authUser(), ["sender", "receiver"]);
        return $this->ok($this->paginate($message));
    }

    public function chat(User $receiver): JsonResponse
    {
        $messages = $this->messageService->getChatMessages($this->authUser(), $receiver, ["sender", "receiver"]);
        return $this->ok($this->paginate($messages));
    }

    public function store(UserMessageRequest $request): JsonResponse
    {
        $data = $request->validated();
        $message = $this->messageService->make($this->authUser(), $data);
        return $this->ok($message);
    }

    public function show(Message $message): JsonResponse
    {
        $message->load(["sender", "receiver", "reply"]);
        return $this->ok($message);
    }

    public function update(UserMessageRequest $request, Message $message): JsonResponse
    {
        $data = $request->validated();
        $message->update($data);
        return $this->ok($message);
    }

    public function destroy(Message $message): JsonResponse
    {
        $message->delete();
        return $this->deleted();
    }
}
