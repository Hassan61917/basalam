<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Common\CommonCommentRequest;
use App\Http\Resources\v1\CommentResource;
use App\Models\Comment;
use App\ModelServices\Social\CommentService;
use Illuminate\Http\JsonResponse;

class AdminCommentController extends Controller
{
    protected string $resource = CommentResource::class;

    public function __construct(
        public CommentService $commentService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $comments = $this->commentService->getComments(["post"]);
        return $this->ok($this->paginate($comments));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommonCommentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $post = $this->commentService->make($this->authUser(), $data);
        return $this->ok($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment): JsonResponse
    {
        $comment->load(["user", 'post', "reply"]);
        return $this->ok($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommonCommentRequest $request, Comment $comment): JsonResponse
    {
        $comment->update($request->validated());
        return $this->ok($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();
        return $this->deleted();
    }
}
