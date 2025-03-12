<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Exceptions\ModelException;
use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserPostRequest;
use App\Http\Resources\v1\PostResource;
use App\Models\Post;
use App\ModelServices\Social\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schedule;

class UserPostController extends AuthUserController
{
    protected string $resource = PostResource::class;

    public function __construct(
        private readonly PostService $postService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $posts = $this->postService->getPostsFor($this->authUser());
        return $this->ok($this->paginate($posts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserPostRequest $request): JsonResponse
    {
        $data = $request->validated();
        $post = $this->postService->make($this->authUser(), $data);
        return $this->ok($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): JsonResponse
    {
        $post->load("user", "comments");
        return $this->ok($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserPostRequest $request, Post $post): JsonResponse
    {
        $data = $request->validated();
        if ($post->isPublished() && array_key_exists("published_at", $data)) {
            throw new ModelException("published posts can not be scheduled");
        }
        $post->update($data);
        return $this->ok($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();
        return $this->deleted();
    }
}
