<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Common\UserMessageRequest;
use App\Http\Resources\v1\MessageResource;
use App\Models\Message;
use App\ModelServices\Social\MessageService;
use Illuminate\Http\JsonResponse;

class AdminMessageController extends Controller
{
    protected string $resource = MessageResource::class;

    public function __construct(
        public MessageService $messageService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $messages = $this->messageService->allMessages();
        return $this->ok($this->paginate($messages));
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message): JsonResponse
    {
        $message->load("sender", "receiver", "reply");
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
