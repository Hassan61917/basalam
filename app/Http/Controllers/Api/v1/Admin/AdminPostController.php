<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserPostRequest;
use App\Http\Resources\v1\PostResource;
use App\Models\Post;
use App\ModelServices\Social\PostService;
use Illuminate\Http\JsonResponse;

class AdminPostController extends Controller
{
    protected string $resource = PostResource::class;

    public function __construct(
        private readonly PostService $postService,
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $posts = $this->postService->getPosts(["page"]);
        return $this->ok($this->paginate($posts));
    }

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
        $post->load('author');
        return $this->ok($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserPostRequest $request, Post $post): JsonResponse
    {
        $data = $request->validated();
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
