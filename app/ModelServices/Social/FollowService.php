<?php

namespace App\ModelServices\Social;

use App\Events\FollowRequestWasCreated;
use App\Exceptions\ModelException;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FollowService
{
    public function getFollowingRequests(User $user): Builder
    {
        return Follow::followRequests($user)
            ->with("follow");
    }

    public function getMyRequests(User $user): Builder
    {
        return Follow::followingRequests($user)
            ->with("user");
    }

    public function isFollowed(User $user, User $follow): bool
    {
        return $user->follows()
            ->where("follow_id", $follow->id)
            ->exists();
    }

    public function follow(User $user, User $followUser): Follow
    {
        if ($this->isFollowed($user, $followUser)) {
            throw new ModelException("already followed");
        }
        $follow = $user->follows()->create(["follow_id" => $followUser->id]);
        if ($followUser->page->is_private) {
            FollowRequestWasCreated::dispatch($follow);
        } else {
            $this->acceptFollow($follow);
        }
        return $follow;
    }

    public function unfollow(User $user, User $follow): void
    {
        if (!$this->isFollowed($user, $follow)) {
            return;
        }
        $user->followings()->where("follow_id", $follow->id)->delete();
    }

    public function acceptFollow(Follow $follow): void
    {
        $follow->update(["accepted" => true]);
    }

    public function rejectFollow(Follow $follow): void
    {
        $follow->update(["accepted" => false]);
    }
}
