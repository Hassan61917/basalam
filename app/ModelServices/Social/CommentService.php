<?php

namespace App\ModelServices\Social;

use App\Exceptions\ModelException;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommentService
{
    public function getComments(array $relations = []): Builder
    {
        return Comment::query()->withFilters()->with($relations);
    }

    public function getPostsComments(User $user, array $relations = []): Builder
    {
        return Comment::query()
            ->whereHas("post", fn($query) => $query->where("user_id",$user->id))
            ->with($relations);
    }

    public function getCommentsFor(User $user, array $relations = []): HasMany
    {
        return $user->comments()->with($relations);
    }


    public function make(User $user, array $data)
    {
        $post = Post::find($data['post_id']);
        if (!$post->can_comment || !$post->user->page->can_comment) {
            throw new ModelException("you are not allowed to comment");
        }
        return $user->comments()->create($data);
    }

    public function reply(User $user, Comment $comment, array $data)
    {
        $data["post_id"] = $comment->post->id;
        $data["parent_id"] = $comment->id;
        return $this->make($user, $data);
    }
}
