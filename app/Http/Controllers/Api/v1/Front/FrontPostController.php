<?php

namespace App\Http\Controllers\Api\v1\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\PostResource;
use App\Models\Post;
use App\ModelServices\Social\PostService;
use App\ModelServices\Social\VisitService;
use Illuminate\Http\JsonResponse;

class FrontPostController extends Controller
{
    protected string $resource = PostResource::class;

    public function __construct(
        private PostService  $postService,
        private VisitService $visitService
    )
    {
    }

    public function index(): JsonResponse
    {
        $posts = $this->postService->getPublishedPosts();
        return $this->ok($this->paginate($posts));
    }

    public function show(Post $post): JsonResponse
    {
        $post->load("user", "comments")->loadCount("visits", "likes", "dislikes");
        $this->visitService->visit($post, $this->authUser(), request()->ip());
        return $this->ok($post);
    }
}
